<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

// Auth Check for WRITE operations
function check_media_access() {
    global $user_role;
    $is_media = ($user_role === 'media' || $user_role === 'admin');
    if (!$is_media) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized: Media role required']);
        exit;
    }
}

$user_id = $_SESSION['db_id'] ?? 0;
$user_role = strtolower($_SESSION['user_role'] ?? 'member');

// Actions that don't require login/media role (public reads)
$public_actions = ['list_events_public', 'list_resources_public'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!in_array($action, $public_actions) && !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// --- HELPER FUNCTIONS ---

// ... Gallery Functions (Existing) ...
function get_albums($conn) {
    $sql = "SELECT a.*, 
            (SELECT COUNT(*) FROM gallery WHERE album_id = a.id) as photo_count,
            (SELECT GROUP_CONCAT(image_url ORDER BY created_at DESC LIMIT 3) FROM gallery WHERE album_id = a.id) as preview_images
            FROM gallery_albums a 
            ORDER BY a.event_date DESC";
    $result = $conn->query($sql);
    $albums = [];
    while ($row = $result->fetch_assoc()) {
        $row['preview_images'] = $row['preview_images'] ? explode(',', $row['preview_images']) : [];
        $albums[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $albums]);
}

function get_album_photos($conn, $album_id) {
    $album_id = (int)$album_id;
    $sql = "SELECT * FROM gallery WHERE album_id = $album_id ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $photos = [];
    while ($row = $result->fetch_assoc()) {
        $photos[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $photos]);
}

function create_album($conn, $user_id) {
    $title = $_POST['title'] ?? 'Untitled Album';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');
    $location = $_POST['location'] ?? '';
    $link = $_POST['external_link'] ?? '';

    $stmt = $conn->prepare("INSERT INTO gallery_albums (title, description, event_date, location, external_link, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $title, $description, $date, $location, $link, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function update_album($conn) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $link = $_POST['external_link'];

    $stmt = $conn->prepare("UPDATE gallery_albums SET title = ?, description = ?, event_date = ?, location = ?, external_link = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $title, $description, $date, $location, $link, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function delete_album($conn, $id) {
    $stmt = $conn->prepare("SELECT image_url FROM gallery WHERE album_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $path = "../" . $row['image_url'];
        if (file_exists($path)) @unlink($path);
    }
    
    $del = $conn->prepare("DELETE FROM gallery_albums WHERE id = ?");
    $del->bind_param("i", $id);
    if ($del->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Delete failed: ' . $conn->error]);
    }
}

function handle_upload($conn, $user_id) {
    if (!isset($_FILES['file'])) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        return;
    }

    $file = $_FILES['file'];
    $title = $_POST['title'] ?? $file['name'];
    $album_id = isset($_POST['album_id']) ? (int)$_POST['album_id'] : null;
    $target_dir = "../uploads/gallery/";
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $target_file = $target_dir . $filename;
    $db_path = "uploads/gallery/" . $filename; 

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $status = 'published';

        $stmt = $conn->prepare("INSERT INTO gallery (title, image_url, status, uploaded_by, album_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $title, $db_path, $status, $user_id, $album_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Upload successful', 'data' => ['id' => $stmt->insert_id, 'image_url' => $db_path]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'DB Error: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File save failed']);
    }
}

function delete_item($conn, $id) {
    $stmt = $conn->prepare("SELECT image_url FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $path = "../" . $row['image_url'];
        if (file_exists($path)) @unlink($path);
    }
    
    $del = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    $del->bind_param("i", $id);
    if ($del->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Delete failed']);
    }
}

// --- EVENTS FUNCTIONS ---

function list_events($conn) {
    $sql = "SELECT * FROM events ORDER BY start_date ASC";
    $result = $conn->query($sql);
    $events = [];
    while ($row = $result->fetch_assoc()) $events[] = $row;
    echo json_encode(['success' => true, 'data' => $events]);
}

// Helper for image upload
function upload_image_file($file, $subfolder = 'events') {
    $target_dir = "../uploads/$subfolder/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $target_file = $target_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return "uploads/$subfolder/" . $filename;
    }
    return null;
}

function create_event($conn, $user_id) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $start = $_POST['date']; // From form input name="date"
    $loc = $_POST['location'];
    
    // Handle optional image
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = upload_image_file($_FILES['image'], 'events');
    }
    
    $status = 'upcoming';
    // Basic status logic
    if (strtotime($start) < time()) $status = 'past';
    
    $stmt = $conn->prepare("INSERT INTO events (title, description, start_date, location, image_url, created_by, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $title, $desc, $start, $loc, $image_url, $user_id, $status);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function update_event($conn) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $start = $_POST['date'];
    $loc = $_POST['location'];
    
    // Check if image is being updated
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_url = upload_image_file($_FILES['image'], 'events');
        $stmt = $conn->prepare("UPDATE events SET title=?, description=?, start_date=?, location=?, image_url=? WHERE id=?");
        $stmt->bind_param("sssssi", $title, $desc, $start, $loc, $image_url, $id);
    } else {
        // Keep existing image
        $stmt = $conn->prepare("UPDATE events SET title=?, description=?, start_date=?, location=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $desc, $start, $loc, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function delete_event($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}


// --- RESOURCES FUNCTIONS ---

function list_resources($conn) {
    $sql = "SELECT r.*, m.full_name as uploader_name FROM resources r LEFT JOIN members m ON r.uploaded_by = m.id ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $resources = [];
    while ($row = $result->fetch_assoc()) $resources[] = $row;
    echo json_encode(['success' => true, 'data' => $resources]);
}

function upload_resource($conn, $user_id) {
    if (!isset($_FILES['file'])) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        return;
    }
    
    $file = $_FILES['file'];
    $title = $_POST['title'] ?? $file['name'];
    $desc = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? 'General';
    
    $target_dir = "../uploads/resources/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $target_file = $target_dir . $filename;
    $db_path = "uploads/resources/" . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO resources (title, description, file_url, file_type, category, uploaded_by, status) VALUES (?, ?, ?, ?, ?, ?, 'published')");
        $stmt->bind_param("sssssi", $title, $desc, $db_path, $ext, $category, $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File upload failed']);
    }
}

function delete_resource($conn, $id) {
    // Determine path
    $stmt = $conn->prepare("SELECT file_url FROM resources WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $path = "../" . $row['file_url'];
        if (file_exists($path)) @unlink($path);
    }
    
    $del = $conn->prepare("DELETE FROM resources WHERE id = ?");
    $del->bind_param("i", $id);
    if ($del->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}


// --- ROUTER ---

switch ($action) {
    // Gallery
    case 'list_albums': get_albums($conn); break;
    case 'get_album_photos': get_album_photos($conn, $_GET['album_id'] ?? 0); break;
    case 'create_album': check_media_access(); create_album($conn, $user_id); break;
    case 'update_album': check_media_access(); update_album($conn); break;
    case 'delete_album': check_media_access(); delete_album($conn, $_POST['id'] ?? 0); break;
    case 'upload_gallery': check_media_access(); handle_upload($conn, $user_id); break;
    case 'delete_gallery': check_media_access(); delete_item($conn, $_POST['id'] ?? 0); break;
    
    // Events
    case 'list_events': 
    case 'list_events_public':
        list_events($conn); 
        break;
    case 'create_event': check_media_access(); create_event($conn, $user_id); break;
    case 'update_event': check_media_access(); update_event($conn); break;
    case 'delete_event': check_media_access(); delete_event($conn, $_POST['id'] ?? 0); break;
    
    // Resources
    case 'list_resources': 
    case 'list_resources_public':
        list_resources($conn); 
        break;
    case 'upload_resource': check_media_access(); upload_resource($conn, $user_id); break;
    case 'delete_resource': check_media_access(); delete_resource($conn, $_POST['id'] ?? 0); break;

    default:
        // Default to albums if logged in
        if (isset($_SESSION['user_id'])) get_albums($conn);
        else echo json_encode(['success' => false, 'message' => 'No action specified']);
        break;
}

$conn->close();
?>
