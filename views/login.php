<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="login" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/icons/favicon-192x192.ico">

    <!-- Theme global (colores, tipografía, botones) -->
    <link rel="stylesheet" href="../style/theme.css">

    <!-- CSS específico del login (layout) -->
    <link rel="stylesheet" href="../style/login.css">

    <!-- Scripts -->
    <script src="../scripts/navigation.js"></script>
</head>

<body>

    <!-- Box where left and right box take place -->
    <div class="container-body">

        <!-- Left Side -->
        <div id="container-left-side">
            <div id="container-left-side-items">

                <form id="loginForm" action="../services/userProvider/login.php" method="POST">
                    <h1>Login</h1>

                    <label for="Email">E-mail*</label>
                    <input
                        placeholder="Geben Sie Ihre E-mail Adresse hier ein"
                        type="text"
                        id="username"
                        name="username"
                        required>

                    <label for="Password">Password*</label>
                    <input
                        placeholder="Geben Sie Ihr Passwort hier ein"
                        type="password"
                        id="password"
                        name="password"
                        required>

                    <div id="error-message" style="color: red;"></div>

                    <br><br>

                    <button type="submit">Login</button>
                </form>

                <br>

                <button onclick="navigateToSignUpPage()">SignUp</button>

                <br><br>

                <div class="form-row">
                    <button
                        class="backHome"
                        onclick="window.location.href='homepage.php'">
                        abbrechen
                    </button>

                    <br><br>

                    <p id="password-reset">
                        <a href="reset_password/reset_password.php" class="password">
                            Passwort vergessen?
                        </a>
                    </p>
                </div>

            </div>
        </div>

        <!-- Right Side -->
        <div id="container-right-side">
            <img height="550px" src="../assets/images/inf-logo.png" alt="FSI Logo">
        </div>

    </div>

</body>
</html>
