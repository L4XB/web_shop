<?php
// Server connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webShopFSI";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the POST request
$productId = $_POST['productId'];
$userId = $_POST['userId'];
$amount = $_POST['amount'];

// SQL query to update the product quantity
$stmt = $conn->prepare("UPDATE shoppingCart SET amount = ? WHERE productID = ? AND userID = ?");
$stmt->bind_param("iii", $amount, $productId, $userId);

if ($stmt->execute()) {
    echo "Quantity updated successfully";
} else {
    echo "Error while updating the quantity: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
