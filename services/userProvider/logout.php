```php
<?php
session_start();

if ($_SESSION['loggedIn'] === true) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";
    $userid = $_SESSION['userId'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE users SET is_logged_in = 0 WHERE userID = ?");

    if ($stmt === false) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $stmt->close();

    $_SESSION['loggedIn'] = false;
    $_SESSION['userId'] = "";

    header('Location: ../../views/homepage.php');
    // session_destroy();
    exit;
}
?>
```
