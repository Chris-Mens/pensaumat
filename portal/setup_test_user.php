<?php
// Setup a specific user with role and password.
// Usage: ?email=...&password=...&role=...&name=...

header('Content-Type: text/plain');
require_once 'db_connect.php';

$email = $_GET['email'] ?? 'media@pensaumat.org';
$password = $_GET['password'] ?? 'media123';
$role = $_GET['role'] ?? 'media';
$name = $_GET['name'] ?? 'Media Admin';

// Check if user exists
$check = $conn->prepare("SELECT id FROM members WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$res = $check->get_result();

$hashed = password_hash($password, PASSWORD_DEFAULT);

if ($res->num_rows > 0) {
    // Update existing
    $row = $res->fetch_assoc();
    $id = $row['id'];
    $update = $conn->prepare("UPDATE members SET password = ?, role = ?, full_name = ? WHERE id = ?");
    $update->bind_param("sssi", $hashed, $role, $name, $id);
    if ($update->execute()) {
        echo "User '$email' UPDATED successfully.\n";
        echo "Password set to: $password\n";
        echo "Role set to: $role";
    } else {
        echo "Error updating: " . $conn->error;
    }
} else {
    // Create new
    $portal_id = 'MEDIA-' . rand(100, 999);
    $insert = $conn->prepare("INSERT INTO members (portal_id, full_name, email, password, role, department) VALUES (?, ?, ?, ?, ?, 'Media')");
    $insert->bind_param("sssss", $portal_id, $name, $email, $hashed, $role);
    if ($insert->execute()) {
        echo "User '$email' CREATED successfully.\n";
        echo "Password set to: $password\n";
        echo "Role set to: $role";
    } else {
        echo "Error creating: " . $conn->error;
    }
}

$conn->close();
?>
