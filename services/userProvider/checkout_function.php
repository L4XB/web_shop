<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webShopFSI";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Get the current user ID
$currentUserId = $_SESSION['userId'];

// Fetch all items from the `shoppingCart` table for the current user
$sql = "SELECT productID, amount FROM shoppingCart WHERE userID = $currentUserId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through all returned rows
    while ($row = $result->fetch_assoc()) {
        $productID = $row['productID'];
        $amount = $row['amount'];

        // Check stock for each product
        $sql = "SELECT stock FROM products WHERE productID = $productID";
        $stockResult = $conn->query($sql);
        $stockRow = $stockResult->fetch_assoc();
        $stock = $stockRow['stock'];

        // If there is not enough stock, redirect the user to an error page
        if ($stock < $amount) {
            header('Location: ../../views/error.php');
            exit();
        }
    }
} else {
    echo "No entries found in the shopping cart table for the current user.";
}

// Generate a random order number
$orderNumber = rand(1000000000, 9999999999);

// Extract the full address and payment method from the POST array
$fullAddress = $conn->real_escape_string($_POST['fullAddress']);
$paymentMethod = $conn->real_escape_string($_POST['paymentMethod']);
$versandart = $conn->real_escape_string($_POST['shippingMethod']);
$gesamtBetrag = $conn->real_escape_string($_POST['totalAmount']);
$betrag = str_replace(['€', ' '], '', $gesamtBetrag);
$name = $conn->real_escape_string($_POST['firstName']);
$email = $conn->real_escape_string($_POST['email']);

$sql = "INSERT INTO transactions (timestamp, userID, orderNumber, adress, paymentMethod)
        VALUES (CURRENT_TIMESTAMP, $currentUserId, $orderNumber, '$fullAddress', '$paymentMethod')";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    // Get the ID of the last inserted record
    $last_id = $conn->insert_id;
    echo "New entry created successfully. The transaction ID is: " . $last_id;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Get the current user ID
$currentUserId = $_SESSION['userId'];

// Insert all entries for the current user from `shoppingCart` into the `history` table
$sql = "INSERT INTO history (timestamp, amount, userID, productID, transactionID)
        SELECT CURRENT_TIMESTAMP, amount, userID, productID, $last_id
        FROM shoppingCart
        WHERE userID = $currentUserId";

if ($conn->query($sql) === TRUE) {
    echo "Entries successfully moved into the history table.";
} else {
    echo "Error while moving entries: " . $conn->error;
}

$sql = "SELECT productID, amount FROM shoppingCart WHERE userID = $currentUserId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through all returned rows
    while ($row = $result->fetch_assoc()) {
        $productID = $row['productID'];
        $amount = $row['amount'];

        // Update stock in the `products` table
        $sql = "UPDATE products SET stock = stock - $amount WHERE productID = $productID";
        if ($conn->query($sql) === TRUE) {
            echo "Stock updated successfully.";
        } else {
            echo "Error while updating stock: " . $conn->error;
        }
    }
} else {
    echo "No entries found in the shopping cart table for the current user.";
}

// Delete data from the `shoppingCart` table for the current user
$sql = "DELETE FROM shoppingCart WHERE userID = $currentUserId";

if ($conn->query($sql) === TRUE) {
    echo "Entries successfully deleted from the shopping cart table.";
} else {
    echo "Error while deleting entries: " . $conn->error;
}

require '../mailer/mailer_checkout.php';
sendConfirmationMail($orderNumber, $versandart, $last_id, $betrag, $name, $email);

header('Location: ../../views/thankyou.php');
?>
