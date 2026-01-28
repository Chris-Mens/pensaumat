<?php
require_once 'db_connect.php';

// Array of columns to add if they don't exist
$columns = [
    "phone VARCHAR(20)",
    "residence VARCHAR(100)",
    "program VARCHAR(100)",
    "level VARCHAR(20)"
];

foreach ($columns as $col) {
    // extract column name
    $col_name = explode(' ', $col)[0];
    
    // Check if column exists
    $check = $conn->query("SHOW COLUMNS FROM members LIKE '$col_name'");
    if ($check->num_rows == 0) {
        $sql = "ALTER TABLE members ADD $col";
        if ($conn->query($sql) === TRUE) {
            echo "Added column: $col_name\n";
        } else {
            echo "Error adding $col_name: " . $conn->error . "\n";
        }
    } else {
        echo "Column $col_name already exists.\n";
    }
}

$conn->close();
?>
