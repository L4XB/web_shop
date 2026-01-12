<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

function isFirstLogin($email)
{
    // Server connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT isFirstLogin FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['isFirstLogin'] == 1;
}

function setFirstLoginToFalse($email)
{
    // Server connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE users SET isFirstLogin = false WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Check whether the update was successful
    if ($stmt->affected_rows === 0) {
        exit('No rows were updated');
    } else {
        echo 'The isFirstLogin field was successfully set to false';
    }

    $stmt->close();
    $conn->close();
}

include '2fa.php';

// Server connection
$servername = "localhost";
$usernamed = "root";
$password = "";
$dbname = "webShopFSI";

$conn = new mysqli($servername, $usernamed, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = hash('sha512', $password);
    $_SESSION['alert'] = "Hash: " . $hashedPassword . "  Clear: " . $password;

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND passwort = ?");
    $stmt->bind_param("ss", $username, $hashedPassword);

    // Execute the statement
    $stmt->execute();

    // Fetch result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['userId'] = $user['userID'];
        $_SESSION['loggedIn'] = true;

        $servername = "localhost";
        $usernamed = "root";
        $password = "";
        $dbname = "webShopFSI";

        $_SESSION['email'] = $username;
        $userid = $_SESSION['userId'];
        $_SESSION['previous_page'] = "login";

        $conn = new mysqli($servername, $usernamed, $password, $dbname);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("UPDATE users SET is_logged_in = 1 WHERE userID = ?");

        if ($stmt === false) {
            die("Error preparing SQL statement: " . $conn->error);
        }

        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $stmt->close();

        $currentTimestamp = date('Y-m-d H:i:s');
        $updateStmt = $conn->prepare("UPDATE users SET lastLogin = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $currentTimestamp, $username);
        $updateStmt->execute();
        $updateStmt->close();

        $_SESSION['name'] = $user['firstName'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastLogIn'] = $user['lastLogIn'];
        $_SESSION['email'] = $username;

        $isFirstLogin = isFirstLogin($_SESSION['email']);
        if ($isFirstLogin) {
            setFirstLoginToFalse($_SESSION['email']);
            if (is2FAEnabled()) {
                header('Location: ../../views/check_2fa.php');
            } else {
                header('Location: ../../views/setNewPassword.php');
            }
        } else {
            if (is2FAEnabled()) {
                header('Location: ../../views/check_2fa.php');
            } else {
                header('Location: ../../views/homepage.php');
            }
        }

    } else {
        header('Location: ../../views/error.php');
    }

    // Close the statement
    $stmt->close();
}

$conn->close();
?>
