<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$current_email = $_SESSION['user_id']; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // FETCH PROFILE
    $stmt = $conn->prepare("SELECT full_name, portal_id, email, phone, department, residence, program, level FROM members WHERE email = ?");
    $stmt->bind_param("s", $current_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
    $stmt->close();
} 
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // UPDATE PROFILE
    $new_full_name = trim($_POST['full_name'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $phone = $_POST['phone'] ?? '';
    $residence = $_POST['residence'] ?? '';
    $program = $_POST['program'] ?? '';
    $level = $_POST['level'] ?? '';
    
    // Validation
    if(empty($new_full_name) || empty($new_email)) {
        echo json_encode(['success' => false, 'message' => 'Name and Email are required']);
        exit;
    }

    // Check if email is being changed
    if ($new_email !== $current_email) {
        // Check uniqueness
        $check = $conn->prepare("SELECT id FROM members WHERE email = ? AND id != (SELECT id FROM members WHERE email = ?)"); 
        // Actually simpler: Select ID from members where email = ? 
        // But we need to make sure we don't count our own row if we didn't change it (logic handled by if), 
        // but here we know it CHANGED. So just check if exists.
        
        $check = $conn->prepare("SELECT id FROM members WHERE email = ?");
        $check->bind_param("s", $new_email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already taken by another user']);
            exit;
        }
        $check->close();
    }

    // Update
    $stmt = $conn->prepare("UPDATE members SET full_name = ?, email = ?, phone = ?, residence = ?, program = ?, level = ? WHERE email = ?");
    $stmt->bind_param("sssssss", $new_full_name, $new_email, $phone, $residence, $program, $level, $current_email);
    
    if ($stmt->execute()) {
        // Update Session if email changed
        if ($new_email !== $current_email) {
            $_SESSION['user_id'] = $new_email;
        }
        // Update name in session too just in case
        $_SESSION['user_name'] = $new_full_name;
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
    }
    $stmt->close();
}
$conn->close();
?>
