<?php
require_once 'db_connect.php';

function executeQuery($conn, $sql, $message) {
    if ($conn->query($sql) === TRUE) {
        echo "Success: $message\n";
    } else {
        echo "Error ($message): " . $conn->error . "\n";
    }
}

// 1. Create Gallery Albums Table
$albums_sql = "CREATE TABLE IF NOT EXISTS gallery_albums (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE,
    cover_image_url VARCHAR(255),
    location VARCHAR(255),
    created_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES members(id) ON DELETE SET NULL
)";
executeQuery($conn, $albums_sql, "Created gallery_albums table");

// 2. Add album_id to gallery table
// Check if column exists first to avoid error
$check_col = "SHOW COLUMNS FROM gallery LIKE 'album_id'";
$result = $conn->query($check_col);
if ($result->num_rows == 0) {
    $alter_sql = "ALTER TABLE gallery ADD COLUMN album_id INT(11) AFTER id";
    if ($conn->query($alter_sql) === TRUE) {
        echo "Success: Added album_id to gallery table\n";
        
        // Add FK
        $fk_sql = "ALTER TABLE gallery ADD CONSTRAINT fk_album FOREIGN KEY (album_id) REFERENCES gallery_albums(id) ON DELETE CASCADE";
        executeQuery($conn, $fk_sql, "Added FK constraint to gallery table");
    } else {
        echo "Error adding album_id: " . $conn->error . "\n";
    }
} else {
    echo "Column album_id already exists in gallery table\n";
}

$conn->close();
?>
