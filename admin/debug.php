<?php
// admin/debug.php
session_start();
include '../main/connect.php';

echo "<h2>Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>User Table Structure:</h2>";
$result = mysqli_query($conn, "DESCRIBE user");
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
}
echo "</table>";

echo "<h2>Sample Users:</h2>";
$result = mysqli_query($conn, "SELECT * FROM user");
echo "<table border='1'>";
if($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    foreach(array_keys($row) as $key) {
        echo "<th>$key</th>";
    }
    echo "</tr>";
    
    do {
        echo "<tr>";
        foreach($row as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    } while($row = mysqli_fetch_assoc($result));
}
echo "</table>";

mysqli_close($conn);
?>