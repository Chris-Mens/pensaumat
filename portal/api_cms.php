<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

// Auth Check - Admin Only
$user_role = strtolower($_SESSION['user_role'] ?? 'member');
if ($user_role !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized: Admin access required']);
    exit;
}

$user_id = $_SESSION['db_id'] ?? 0;
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ==================== CMS PAGES ====================

function get_cms_content($conn) {
    $page = $_GET['page'] ?? 'all';
    
    if ($page === 'all') {
        $sql = "SELECT * FROM cms_pages ORDER BY page_slug, section_name";
    } else {
        $sql = "SELECT * FROM cms_pages WHERE page_slug = '" . $conn->real_escape_string($page) . "' ORDER BY section_name";
    }
    
    $result = $conn->query($sql);
    $content = [];
    while ($row = $result->fetch_assoc()) {
        $content[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $content]);
}

function update_cms_content($conn) {
    $id = (int)$_POST['id'];
    $content_text = $_POST['content_text'] ?? '';
    $content_html = $_POST['content_html'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    
    // Handle image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = upload_image($_FILES['image'], 'cms');
    }
    
    $stmt = $conn->prepare("UPDATE cms_pages SET content_text = ?, content_html = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("sssi", $content_text, $content_html, $image_url, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function create_cms_section($conn) {
    $page_slug = $_POST['page_slug'];
    $section_name = $_POST['section_name'];
    $content_text = $_POST['content_text'] ?? '';
    
    $stmt = $conn->prepare("INSERT INTO cms_pages (page_slug, section_name, content_text) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $page_slug, $section_name, $content_text);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

// ==================== ANNOUNCEMENTS ====================

function list_announcements($conn) {
    $sql = "SELECT a.*, m.full_name as author_name 
            FROM announcements a 
            LEFT JOIN members m ON a.created_by = m.id 
            ORDER BY is_pinned DESC, created_at DESC";
    
    $result = $conn->query($sql);
    $announcements = [];
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $announcements]);
}

function create_announcement($conn, $user_id) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $type = $_POST['type'] ?? 'general';
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    $expires_at = $_POST['expires_at'] ?? null;
    
    $stmt = $conn->prepare("INSERT INTO announcements (title, message, type, is_pinned, expires_at, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisi", $title, $message, $type, $is_pinned, $expires_at, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function update_announcement($conn) {
    $id = (int)$_POST['id'];
    $title = $_POST['title'];
    $message = $_POST['message'];
    $type = $_POST['type'];
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    $expires_at = $_POST['expires_at'] ?? null;
    $status = $_POST['status'] ?? 'active';
    
    $stmt = $conn->prepare("UPDATE announcements SET title=?, message=?, type=?, is_pinned=?, expires_at=?, status=? WHERE id=?");
    $stmt->bind_param("sssissi", $title, $message, $type, $is_pinned, $expires_at, $status, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function delete_announcement($conn) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

// ==================== DEPARTMENTS ====================

function list_departments($conn) {
    $sql = "SELECT * FROM departments ORDER BY name ASC";
    $result = $conn->query($sql);
    $departments = [];
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $departments]);
}

function create_department($conn) {
    $name = $_POST['name'];
    $description = $_POST['description'] ?? '';
    $leader_name = $_POST['leader_name'] ?? '';
    $leader_contact = $_POST['leader_contact'] ?? '';
    $meeting_day = $_POST['meeting_day'] ?? '';
    $meeting_time = $_POST['meeting_time'] ?? '';
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = upload_image($_FILES['image'], 'departments');
    }
    
    $stmt = $conn->prepare("INSERT INTO departments (name, description, leader_name, leader_contact, meeting_day, meeting_time, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $description, $leader_name, $leader_contact, $meeting_day, $meeting_time, $image_url);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function update_department($conn) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $leader_name = $_POST['leader_name'];
    $leader_contact = $_POST['leader_contact'];
    $meeting_day = $_POST['meeting_day'];
    $meeting_time = $_POST['meeting_time'];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = upload_image($_FILES['image'], 'departments');
        $stmt = $conn->prepare("UPDATE departments SET name=?, description=?, leader_name=?, leader_contact=?, meeting_day=?, meeting_time=?, image_url=? WHERE id=?");
        $stmt->bind_param("sssssssi", $name, $description, $leader_name, $leader_contact, $meeting_day, $meeting_time, $image_url, $id);
    } else {
        $stmt = $conn->prepare("UPDATE departments SET name=?, description=?, leader_name=?, leader_contact=?, meeting_day=?, meeting_time=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $description, $leader_name, $leader_contact, $meeting_day, $meeting_time, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function delete_department($conn) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM departments WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

// ==================== SITE SETTINGS ====================

function get_settings($conn) {
    $sql = "SELECT * FROM site_settings ORDER BY setting_key";
    $result = $conn->query($sql);
    $settings = [];
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    echo json_encode(['success' => true, 'data' => $settings]);
}

function update_setting($conn) {
    $key = $_POST['key'];
    $value = $_POST['value'];
    
    $stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    $stmt->bind_param("sss", $key, $value, $value);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

// ==================== CONTACT MESSAGES ====================

function list_contact_messages($conn) {
    $sql = "SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC LIMIT 100";
    $result = $conn->query($sql);
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $messages]);
}

function mark_message_read($conn) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function delete_contact_message($conn) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

// ==================== HELPER FUNCTIONS ====================

function upload_image($file, $subfolder = 'cms') {
    $target_dir = "../uploads/$subfolder/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $target_file = $target_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return "uploads/$subfolder/" . $filename;
    }
    return null;
}

// ==================== ROUTER ====================

switch ($action) {
    // CMS Pages
    case 'get_cms_content': get_cms_content($conn); break;
    case 'update_cms_content': update_cms_content($conn); break;
    case 'create_cms_section': create_cms_section($conn); break;
    
    // Announcements
    case 'list_announcements': list_announcements($conn); break;
    case 'create_announcement': create_announcement($conn, $user_id); break;
    case 'update_announcement': update_announcement($conn); break;
    case 'delete_announcement': delete_announcement($conn); break;
    
    // Departments
    case 'list_departments': list_departments($conn); break;
    case 'create_department': create_department($conn); break;
    case 'update_department': update_department($conn); break;
    case 'delete_department': delete_department($conn); break;
    
    // Settings
    case 'get_settings': get_settings($conn); break;
    case 'update_setting': update_setting($conn); break;
    
    // Contact Messages
    case 'list_contact_messages': list_contact_messages($conn); break;
    case 'mark_message_read': mark_message_read($conn); break;
    case 'delete_contact_message': delete_contact_message($conn); break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

$conn->close();
?>
