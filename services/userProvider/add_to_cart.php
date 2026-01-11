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

// Get data from POST request
$productId = $_POST['productId'];
$userId = $_POST['userId'];
$amount = $_POST['amount'];

// SQL query to insert data into the table
$stmt = $conn->prepare("INSERT INTO shoppingCart (productID, userID, amount) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $productId, $userId, $amount);

if ($stmt->execute()) {
    echo "Product successfully added to the shopping cart";
} else {
    echo "Error while adding the product to the shopping cart: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
