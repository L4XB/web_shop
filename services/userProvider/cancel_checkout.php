<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webShopFSI";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentUserId = $_SESSION['userId'];

// Delete data from the shoppingCart table for the current user
$sql = "DELETE FROM shoppingCart WHERE userID = $currentUserId";

if ($conn->query($sql) === TRUE) {
    echo "Entries successfully deleted from the shopping cart table.";
} else {
    echo "Error while deleting entries: " . $conn->error;
}

header('Location: ../../views/homepage.php');
?>
