<?php
function updateVerificationCode($code, $email)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    // Create the database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE users SET verificationCode = ? WHERE email = ?");
    $stmt->bind_param("ss", $code, $email);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Verification code updated successfully.";
    } else {
        echo "Error updating verification code: " . $stmt->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
