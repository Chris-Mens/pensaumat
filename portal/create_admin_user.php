<?php
require_once 'db_connect.php';

$email = 'admin@pensaumat.org';
$password = 'admin123';
$role = 'admin';
$name = 'System Administrator';

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
        echo "Admin User UPDATED.\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
} else {
    // Create new
    $portal_id = 'ADMIN-' . rand(100, 999);
    $insert = $conn->prepare("INSERT INTO members (portal_id, full_name, email, password, role, department) VALUES (?, ?, ?, ?, ?, 'Administration')");
    $insert->bind_param("sssss", $portal_id, $name, $email, $hashed, $role);
    if ($insert->execute()) {
        echo "Admin User CREATED.\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}

echo "Email: $email\n";
echo "Password: $password\n";
?>
