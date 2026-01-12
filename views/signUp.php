<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="signUp" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/icons/favicon-192x192.ico">

    <!-- Page CSS (layout específico) -->
    <link rel="stylesheet" href="../style/signUp.css">
    <link rel="stylesheet" href="../style/login.css">

    <!-- Global theme (colores, tipografía, botones) -->
    <!-- IMPORTANTE: al final para que sobrescriba el amarillo -->
    <link rel="stylesheet" href="../style/theme.css">

    <!-- Scripts -->
    <script src="../scripts/navigation.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#signUpForm').on('submit', function (e) {
                e.preventDefault();
                var email = $('#email').val();
                $.ajax({
                    type: 'POST',
                    url: '../services/userProvider/checkMail.php',
                    data: { email: email },
                    success: function (data) {
                        if (data == 'exists') {
                            $('#emailError').text('E-Mail schon vorhanden');
                        } else {
                            $('#signUpForm').unbind('submit').submit();
                        }
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="container-body">
        <!-- Left Side -->
        <div id="container-left-side">
            <div id="container-left-side-items">
                <form id="signUpForm" method="post" action="../mailer.php">
                    <h1>Account erstellen</h1>

                    <div class="form-field">
                        <label for="email">E-Mail:</label>
                        <input placeholder="Geben Sie Ihre E-mail ein" style="width: 200%;" type="email" id="email"
                            name="email" required>
                    </div>

                    <br>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="firstName">Vorname:</label>
                            <input placeholder="Geben Sie Ihren Vornamen ein" type="text" id="firstName"
                                name="firstName" style="width: 90%;" required>
                        </div>

                        <div class="form-field">
                            <label for="lastName">Nachname:</label>
                            <input placeholder="Geben Sie Ihren Nachnamen ein" type="text" id="lastName" name="lastName"
                                style="width: 90%;" required>
                        </div>
                    </div>

                    <br>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="password">Telefonnummer:</label>
                            <input placeholder="Geben Sie Ihren Telefonnummer ein" type="tel" id="phoneNumber"
                                name="phoneNumber" style="width: 200%;" required>
                        </div>
                    </div>

                    <br>

                    <div id="emailError" style="color: red;text-align:center;"></div>

                    <br>

                    <div class="form-row">
                        <button class="backHome" onclick="window.location.href='homepage.php'">abbrechen</button>
                        <button class="weiter" name="submit" type="submit">Weiter</button>
                    </div>

                    <hr class="thin-black-line">

                    <div id="bottom-options">
                        <p>Sie haben schon einen Account?</p>
                        <a style="text-decoration: none;" href="login.php">
                            <p id="back-to-login">Login</p>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side -->
        <div id="container-right-side">
            <img height="550px" src="../assets/images/inf-logo.png" alt="">
        </div>
    </div>

    <script>
        document.getElementById('email').addEventListener('blur', function () {
            var email = this.value;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../services/userProvider/check_email_real_time.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.exists) {
                        document.getElementById('emailError').textContent = 'Nutzername schon vergeben';
                    } else {
                        document.getElementById('emailError').textContent = '';
                    }
                }
            };
            xhr.send('email=' + email);
        });
    </script>
</body>

</html>
