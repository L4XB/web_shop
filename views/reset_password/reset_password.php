<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="reset_password" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link rel="icon" type="image/x-icon" href="../../assets/icons/favicon-192x192.ico">

    <!-- Page CSS (layout) -->
    <link rel="stylesheet" href="../../style/login.css">

    <!-- Global theme (colors, fonts, buttons) -->
    <link rel="stylesheet" href="../../style/theme.css">
</head>

<body>
    <div class="container-body">
        <div id="container-left-side">
            <div id="container-left-side-items">
                <form id="loginForm" action="../../services/mailer/reset_password.php" method="POST">
                    <h1>Reset Password</h1>

                    <label for="Email">E-mail*</label>
                    <input
                        placeholder="Geben Sie die Email Adresse Ihres Kontos ein"
                        type="text"
                        id="username"
                        name="username"
                        required>

                    <div id="error-message" style="color: red;"></div>

                    <br><br>

                    <button type="submit">Password zurücksetzen</button>

                    <br>
                </form>
            </div>
        </div>

        <div id="container-right-side">
            <img height="550px" src="../../assets/images/inf-logo.png" alt="FSI Logo">
        </div>
    </div>
</body>

</html>
