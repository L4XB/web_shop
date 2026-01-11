<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webShopFSI";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT COUNT(*) AS count FROM users WHERE is_logged_in = 1");
$row = $result->fetch_assoc();
echo $row['count'];
?>
