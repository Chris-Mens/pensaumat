<?php
require_once 'db_connect.php';

echo "Database: " . $dbname . "\n";

// List Tables
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Tables found:\n";
    while($row = $result->fetch_array()) {
        $table = $row[0];
        echo "- " . $table . "\n";
        
        // List Columns for each table
        $col_sql = "SHOW COLUMNS FROM " . $table;
        $col_result = $conn->query($col_sql);
        if ($col_result->num_rows > 0) {
            while($col = $col_result->fetch_assoc()) {
                echo "    " . $col['Field'] . " (" . $col['Type'] . ")\n";
            }
        }
    }
} else {
    echo "No tables found in database '$dbname'.\n";
}

$conn->close();
?>
