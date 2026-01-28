<?php
// A simple script to update a user's role to 'media' for testing.
// Usage: http://localhost/pensaug.org/portal/update_role.php?email=USER_EMAIL&role=media

header('Content-Type: text/plain');
require_once 'db_connect.php';

$email = $_GET['email'] ?? '';
$role = $_GET['role'] ?? 'media';

if (empty($email)) {
    echo "Usage: ?email=your_email@example.com&role=media\n";
    echo "Please provide the email address of the user you want to update.";
    exit;
}

$stmt = $conn->prepare("UPDATE members SET role = ? WHERE email = ?");
$stmt->bind_param("ss", $role, $email);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Success: User '$email' role updated to '$role'.";
    } else {
        echo "Notice: User found but role was already '$role', or user does not exist.";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
