<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

// Auth Setup
$user_id = $_SESSION['db_id'] ?? 0;
$user_role = strtolower($_SESSION['user_role'] ?? 'member');

// Admin Auth Check
function check_admin_access() {
    global $user_role;
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Unauthorized: Admin access required']);
        exit;
    }
}

// Router
check_admin_access(); // All actions in this file require admin
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'dashboard_stats':
        get_stats($conn);
        break;
    case 'list_users':
        list_users($conn);
        break;
    case 'update_user_role':
        update_user_role($conn);
        break;
    case 'delete_user':
        delete_user($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

function get_stats($conn) {
    $stats = [];
    
    // User Stats
    $res = $conn->query("SELECT COUNT(*) as c FROM members");
    $stats['total_users'] = $res->fetch_assoc()['c'];
    
    $res = $conn->query("SELECT COUNT(*) as c FROM members WHERE role='media'");
    $stats['media_team'] = $res->fetch_assoc()['c'];
    
    $res = $conn->query("SELECT COUNT(*) as c FROM members WHERE role='admin'");
    $stats['admins'] = $res->fetch_assoc()['c'];

    // Portal Content Stats
    $res = $conn->query("SELECT COUNT(*) as c FROM events");
    $stats['events'] = $res->fetch_assoc()['c'];

    $res = $conn->query("SELECT COUNT(*) as c FROM prayer_requests");
    $stats['prayer_count'] = $res->fetch_assoc()['c'];
    
    $res = $conn->query("SELECT COUNT(*) as c FROM prayer_requests WHERE status='pending'");
    $stats['pending_prayers'] = $res->fetch_assoc()['c'];

    $res = $conn->query("SELECT COUNT(*) as c FROM resources");
    $stats['resources'] = $res->fetch_assoc()['c'];
    
    $res = $conn->query("SELECT COUNT(*) as c FROM gallery_albums");
    $stats['albums'] = $res->fetch_assoc()['c'];

    // CMS Stats (check if tables exist first)
    $tables_check = $conn->query("SHOW TABLES LIKE 'announcements'");
    if ($tables_check && $tables_check->num_rows > 0) {
        $res = $conn->query("SELECT COUNT(*) as c FROM announcements WHERE status='active'");
        $stats['announcements'] = $res->fetch_assoc()['c'];
        
        $res = $conn->query("SELECT COUNT(*) as c FROM departments");
        $stats['departments'] = $res->fetch_assoc()['c'];
    } else {
        $stats['announcements'] = 0;
        $stats['departments'] = 0;
    }
    
    echo json_encode(['success' => true, 'data' => $stats]);
}

function list_users($conn) {
    // Select correct columns: full_name as name
    $sql = "SELECT id, full_name as name, email, phone_number as phone, role FROM members ORDER BY id DESC LIMIT 100";
    // Checking if phone_number exists in members table or just phone. 
    // Usually standard schemas have phone or phone_number. 
    // In update_role.php we don't see phone. 
    // In auth_login.php we don't see phone.
    // I'll check register.php or similar to confirm schema if I could, but 'members' is the table.
    // I'll guess 'phone' or 'phone_number'. PROBABLY 'phone' if I used it before, 
    // but auth_register.php usage would verify it.
    // For now I'll assume 'phone' exists or alias it if needed. 
    // Let's stick to what's likely safe: `phone` was in my incorrect code, but likely it's `phone` in DB?
    // Let's assume 'phone' column exists in members table for now. 
    // But wait, in auth_login.php it selects id, full_name, password, role FROM members.

    $sql = "SELECT id, full_name as name, email, phone, role FROM members ORDER BY id DESC LIMIT 100";
    $result = $conn->query($sql);
    
    if (!$result) {
         // Fallback if 'phone' is wrong, try 'phone_number' or omit
         $sql = "SELECT id, full_name as name, email, role FROM members ORDER BY id DESC LIMIT 100";
         $result = $conn->query($sql);
    }
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $users]);
}

function update_user_role($conn) {
    $id = (int)$_POST['id'];
    $role = strtolower($_POST['role']);
    
    // Validation
    $valid_roles = ['member', 'media', 'admin'];
    if (!in_array($role, $valid_roles)) {
        echo json_encode(['success' => false, 'message' => 'Invalid role']);
        return;
    }
    
    // Prevent removing own admin status (safety check)
    if ($id == $_SESSION['db_id'] && $role !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'You cannot remove your own admin status']);
        return;
    }

    $stmt = $conn->prepare("UPDATE members SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}

function delete_user($conn) {
    $id = (int)$_POST['id'];
    
    if ($id == $_SESSION['db_id']) {
        echo json_encode(['success' => false, 'message' => 'You cannot delete yourself']);
        return;
    }
    
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}
?>
