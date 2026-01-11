<?php
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    require realpath('C:/xampp/htdocs/webShop/vendor/autoload.php');
} else if (strtoupper(substr(PHP_OS, 0, 6)) === 'DARWIN') {
    require realpath('/Applications/XAMPP/xamppfiles/projekte/webShop/vendor/autoload.php');
} else {
    echo 'This is a server not using Windows or macOS!';
}

function createSecret()
{
    $ga = new PHPGangsta_GoogleAuthenticator();
    $secret = $ga->createSecret();
    echo "Secret is: " . $secret . "\n\n";
    return $secret;
}

function getQRCode($secret)
{
    $ga = new PHPGangsta_GoogleAuthenticator();
    $qrCodeUrl = $ga->getQRCodeGoogleUrl('INF-Webshop', $secret);
    return $qrCodeUrl;
}

function isCodeValid($secret, $oneCode)
{
    $ga = new PHPGangsta_GoogleAuthenticator();
    $checkResult = $ga->verifyCode($secret, $oneCode, 2);
    if ($checkResult) {
        return true;
    } else {
        return false;
    }
}

function get2FASecret()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    session_start();

    $email = $_SESSION['email'];
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT twoFASecret FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Fetch results
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stmt->close(); // Close the statement
        $conn->close(); // Close the connection
        return $user['twoFASecret'];
    } else {
        $stmt->close(); // Close the statement
        $conn->close(); // Close the connection
        return null;
    }
}

function enable2FA()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_SESSION['email'];
    $sql = "UPDATE users SET use2FA = true WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
}

function disable2FA()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_SESSION['email'];
    $sql = "UPDATE users SET use2FA = false WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
}

function is2FAEnabled()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_SESSION['email'];
    $sql = "SELECT use2FA FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['use2FA'];
}
?>
