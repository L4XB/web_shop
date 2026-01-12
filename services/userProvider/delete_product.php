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

// SQL query to delete the product
$stmt = $conn->prepare("DELETE FROM shoppingCart WHERE productID = ? AND userID = ?");
$stmt->bind_param("ii", $productId, $userId);

if ($stmt->execute()) {
    echo "Product successfully removed from the shopping cart";
} else {
    echo "Error while removing the product from the shopping cart: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: ../../views/cart.php"); // Redirects the user to the shopping cart page
exit;
?>
