<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webShopFSI";

session_start();
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['userId'];
$sql = "UPDATE users SET use2FA = false WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();

header('Location: ../../views/profil.php');
?>
