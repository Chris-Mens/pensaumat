<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['db_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_db_id = $_SESSION['db_id']; // This is the internal integer ID we grabbed during login

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's a Status Update (Admin Only)
    if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
        $user_role = $_SESSION['user_role'] ?? 'member';
        if ($user_role !== 'admin' && $user_role !== 'prayer_secretary') {
             echo json_encode(['success' => false, 'message' => 'Unauthorized']);
             exit;
        }

        $id = (int)$_POST['id'];
        $status = $_POST['status']; // 'read', 'prayed', 'replied'

        $stmt = $conn->prepare("UPDATE prayer_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
        $stmt->close();
        exit;
    }

    // Submit new request (Standard User)
    $type = $_POST['request_type'] ?? 'General';
    $text = trim($_POST['request_text'] ?? '');
    $anon = isset($_POST['is_anonymous']) ? 1 : 0;

    if (empty($text)) {
        echo json_encode(['success' => false, 'message' => 'Prayer request cannot be empty.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO prayer_requests (user_id, request_type, request_text, is_anonymous) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $user_db_id, $type, $text, $anon);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Your prayer request has been received.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if we want all requests (Admin Only)
    $view_all = isset($_GET['view']) && $_GET['view'] === 'all';
    $user_role = $_SESSION['user_role'] ?? 'member';

    if ($view_all && ($user_role === 'admin' || $user_role === 'prayer_secretary')) {
        // Fetch ALL requests with user info
        // We join with members table to get names (unless anonymous)
        $sql = "SELECT pr.id, pr.request_type, pr.request_text, pr.status, pr.created_at, pr.is_anonymous, m.full_name, m.phone_number 
                FROM prayer_requests pr 
                JOIN members m ON pr.user_id = m.id 
                ORDER BY pr.created_at DESC";
        
        $result = $conn->query($sql);
        $history = [];
        while ($row = $result->fetch_assoc()) {
            // Anonymize if needed for the display logic, but Admin might need to know? 
            // Usually 'Anonymous' requests are anonymous to public, but Pastor/Prayer Sec might need to know.
            // For now, let's pass the data and let the frontend decide or handle sensitive info.
            // But strict anonymity means we shouldn't send the name if is_anonymous=1.
            // However, usually pastoral care requires knowing who it is. Let's send the name but flag it.
            $history[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $history]);

    } else {
        // Get My History
        $stmt = $conn->prepare("SELECT request_type, request_text, status, created_at FROM prayer_requests WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_db_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $history = [];
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $history]);
        $stmt->close();
    }
}

$conn->close();
?>
