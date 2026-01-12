<?php
require 'updateVerificationStatus.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        session_start();

        // Retrieve the variable from the session
        $emailUser = $_SESSION['emailUser'];
        $numberOne = $_POST['numberOne'];
        $numberTwo = $_POST['numberTwo'];
        $numberThree = $_POST['numberThree'];
        $numberFour = $_POST['numberFour'];
        $numberFive = $_POST['numberFive'];
        $numberSix = $_POST['numberSix'];

        $codeFromInput = $numberOne . $numberTwo . $numberThree . $numberFour . $numberFive . $numberSix;
        $codeFromInput = strval($codeFromInput);

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "webShopFSI";
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Prepare and bind
        $stmt = $conn->prepare("SELECT resetCode FROM users WHERE email = ?");
        $stmt->bind_param("s", $emailUser);

        // Execute the statement
        $stmt->execute();

        // Bind the result
        $stmt->bind_result($codeFromDb);

        // Fetch the result
        $stmt->fetch();

        if ($codeFromDb == $codeFromInput) {
            echo "The entered code is correct.";
            header('Location: ../../views/setNewPassword.php');
        } else {
            header('Location: ../../views/error.php');
        }

        // Close the statement and the connection
        $stmt->close();
        $conn->close();
    }
}
?>
