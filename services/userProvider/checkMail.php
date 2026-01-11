<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Basic validation (keeps behavior simple and predictable)
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'not exists';
        $conn->close();
        exit;
    }

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT 1 FROM users WHERE email = ? LIMIT 1");
    if (!$stmt) {
        // If prepare fails, avoid leaking details to the client
        echo 'not exists';
        $conn->close();
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'exists';
    } else {
        echo 'not exists';
    }

    $stmt->close();
}

$conn->close();
?>
