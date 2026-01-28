<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'logged_in' => true,
        'user_id' => $_SESSION['user_id'],
        'user_name' => $_SESSION['user_name'] ?? 'Member',
        'user_role' => $_SESSION['user_role'] ?? 'member'
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>
