<?php
include '2fa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "webShopFSI";

    // Erstellen der Verbindung
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Überprüfen der Verbindung
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }
    $password = $_POST['password'];
    $confirmPassword = $_POST['passwordSe'];

    if ($password === $confirmPassword) {
        $hashedPassword = hash('sha512', $password);
        session_start();
        $_SESSION['loggedIn'] = true;
        $mail = $_SESSION['email'];
        $sql = "UPDATE users SET passwort = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $mail);
        $stmt->execute();

        $sql = "UPDATE users SET isFirstLogin = false WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $mail);
        $stmt->execute();


        echo "Passwort erfolgreich aktualisiert.";
        if (is2FAEnabled()) {
            header('Location: ../../views/check_2fa.php');
        } else {
            header('Location: ../../views/homepage.php');
        }
    } else {
        echo "Die Passwörter stimmen nicht überein.";
    }
}
?>