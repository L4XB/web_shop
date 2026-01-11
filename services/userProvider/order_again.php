<?php
// Start the session
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$currentUserId = $_SESSION['userId'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webShopFSI";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Make sure a transaction ID was provided
$sql = "DELETE FROM shoppingCart WHERE userID = $currentUserId";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Entries successfully deleted.";
} else {
    echo "Error while deleting entries: " . $conn->error;
}

// Get the provided transaction ID
$transactionId = $_POST['transactionId'];

echo '<script type="text/javascript">';
echo 'alert("Transaction ID: ' . $transactionId . '");';
echo '</script>';

// Create an SQL query to fetch all products for the given transaction ID
$sql = "SELECT * FROM history WHERE transactionID = $transactionId";

// Execute the SQL query
$result = $conn->query($sql);

// Check whether the query was successful
if ($result->num_rows > 0) {
    // Loop through each row in the result
    while ($row = $result->fetch_assoc()) {
        // Extract product ID and amount from the row
        $productId = $row['productID'];
        $amount = $row['amount'];

        // Create an SQL query to add the product and amount to the shoppingCart table
        $sql = "INSERT INTO shoppingCart (userID, productID, amount) VALUES ($currentUserId, $productId, $amount)";

        // Execute the SQL query
        if ($conn->query($sql) === TRUE) {
            echo "Product successfully added to the shopping cart.";
        } else {
            echo "Error while adding the product to the shopping cart: " . $conn->error;
        }
    }
    header('Location: ../../views/checkout.php');
} else {
    echo "No products found for this transaction ID.";
}
?>
