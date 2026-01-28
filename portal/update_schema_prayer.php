<?php
require_once 'db_connect.php';

// Create prayer_requests table
$sql = "CREATE TABLE IF NOT EXISTS prayer_requests (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    request_type VARCHAR(50) NOT NULL, 
    request_text TEXT NOT NULL,
    is_anonymous BOOLEAN DEFAULT 0,
    status VARCHAR(20) DEFAULT 'Pending', -- Pending, Prayed For
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'prayer_requests' created or already exists.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$conn->close();
?>
