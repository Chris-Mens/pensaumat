<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get raw POST data
    $full_name = trim($_POST['full_name'] ?? '');
    $student_id = trim($_POST['student_id'] ?? '') ?: null; 
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '') ?: null;
    $program = trim($_POST['program'] ?? '') ?: null;
    $level = trim($_POST['level'] ?? '') ?: null;
    $residence = trim($_POST['residence'] ?? '') ?: null;
    $department = trim($_POST['department'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($full_name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Full Name, Email, and Password are required.']);
        exit;
    }

    // Check if Email already exists
    $check_stmt = $conn->prepare("SELECT id FROM members WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered.']);
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();
    
    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'member';

    // Insert User with ALL fields
    // Columns: portal_id, full_name, email, password, department, role, phone, residence, program, level
    $insert_stmt = $conn->prepare("INSERT INTO members (portal_id, full_name, email, password, department, role, phone, residence, program, level) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_stmt->bind_param("ssssssssss", $student_id, $full_name, $email, $hashed_password, $department, $role, $phone, $residence, $program, $level);

    if ($insert_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $insert_stmt->error]);
    }

    $insert_stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
