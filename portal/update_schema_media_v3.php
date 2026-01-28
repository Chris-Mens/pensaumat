<?php
require_once 'db_connect.php';

// Add external_link column to gallery_albums if it doesn't exist
$sql = "SHOW COLUMNS FROM gallery_albums LIKE 'external_link'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $alter = "ALTER TABLE gallery_albums ADD COLUMN external_link VARCHAR(500) DEFAULT NULL AFTER location";
    if ($conn->query($alter)) {
        echo "Successfully added 'external_link' column to gallery_albums table.";
    } else {
        echo "Error adding column: " . $conn->error;
    }
} else {
    echo "Column 'external_link' already exists.";
}

$conn->close();
?>
