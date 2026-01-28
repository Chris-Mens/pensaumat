<?php
require_once 'db_connect.php';

function executeQuery($conn, $sql, $message) {
    if ($conn->query($sql) === TRUE) {
        echo "Success: $message\n";
    } else {
        echo "Error ($message): " . $conn->error . "\n";
    }
}

// 1. Events Table
$events_sql = "CREATE TABLE IF NOT EXISTS events (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATETIME,
    end_date DATETIME,
    location VARCHAR(255),
    image_url VARCHAR(255),
    status VARCHAR(20) DEFAULT 'draft',
    created_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES members(id) ON DELETE SET NULL
)";
executeQuery($conn, $events_sql, "Created events table");

// 2. Gallery Table
$gallery_sql = "CREATE TABLE IF NOT EXISTS gallery (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    image_url VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    status VARCHAR(20) DEFAULT 'pending',
    uploaded_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES members(id) ON DELETE SET NULL
)";
executeQuery($conn, $gallery_sql, "Created gallery table");

// 3. Resources Table
$resources_sql = "CREATE TABLE IF NOT EXISTS resources (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_url VARCHAR(255) NOT NULL,
    file_type VARCHAR(50),
    category VARCHAR(50),
    status VARCHAR(20) DEFAULT 'draft',
    uploaded_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES members(id) ON DELETE SET NULL
)";
executeQuery($conn, $resources_sql, "Created resources table");

$conn->close();
?>
