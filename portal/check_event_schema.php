<?php
require_once 'db_connect.php';

$sql = "DESCRIBE events";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error describing events table: " . $conn->error;
}
$conn->close();
?>
