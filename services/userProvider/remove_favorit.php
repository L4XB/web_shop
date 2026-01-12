<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

function removeFavorite($productId, $userId)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM favorites WHERE productId = ? AND userId = ?");
    $stmt->bind_param("ii", $productId, $userId);

    if ($stmt->execute()) {
        echo "Favorite successfully removed";
    } else {
        echo "Error while removing the favorite: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

removeFavorite($_POST['productId'], $_SESSION['userId']);
?>
