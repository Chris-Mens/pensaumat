<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Credentials required']);
        exit;
    }

    // Check credentials using EMAIL
    // Querying 'members' table for 'email' column
    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Success
            $_SESSION['user_id'] = $email; // Using email as user_id session var for now
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['db_id'] = $user['id'];

            echo json_encode([
                'success' => true,
                'role' => $user['role'],
                'user_id' => $user['id']
            ]);
        } else {
            // Invalid password
            echo json_encode(['success' => false, 'message' => 'Invalid Password']);
        }
    } else {
        // User not found
        echo json_encode(['success' => false, 'message' => 'Email not found']);
    }

    $stmt->close();
    $conn->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
