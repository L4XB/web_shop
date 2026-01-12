<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'login.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract data from the input fields
    $numberOne = $_POST['numberOne'];
    $numberTwo = $_POST['numberTwo'];
    $numberThree = $_POST['numberThree'];
    $numberFour = $_POST['numberFour'];
    $numberFive = $_POST['numberFive'];
    $numberSix = $_POST['numberSix'];

    // Combine the data into a single code
    $code = $numberOne . $numberTwo . $numberThree . $numberFour . $numberFive . $numberSix;
    $secret = get2FASecret();

    // Verify the code using the isCodeValid method
    if (isCodeValid($secret, $code)) {
        // If the code is valid, redirect to the homepage

        $_SESSION['2FAAktiv'] = true;
        enable2FA();
        session_start();
        $_SESSION['previous_page'] = "login";

        $isFirstLogin2 = isFirstLogin($_SESSION['email']);
        if ($isFirstLogin2) {
            header('Location: ../../views/setNewPassword.php');
        } else {
            header('Location: ../../views/homepage.php');
        }

        exit;
    } else {
        // If the code is invalid, display an error message
        echo "The entered code is invalid.";
    }
}
?>
