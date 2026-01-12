<?php

function updateVerificationStatus()
{
    // Start the session
    session_start();

    // Retrieve the email address from the session
    $email = $_SESSION['emailUser'];

    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "webshop";

    // Create the connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE users SET isVerified = 'true' WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Verification status updated successfully.";
    } else {
        echo "Error updating verification status: " . $stmt->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
