<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- viewport correcto -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebShop</title>

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="../assets/icons/favicon-192x192.ico">

    <!-- Fonts (Inter + Fraunces) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Theme CSS (después de Bootstrap para que tenga prioridad) -->
    <link rel="stylesheet" href="../style/theme.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style/header.css">

    <!-- Bootstrap JS (defer para no bloquear render) -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button2 = document.getElementById("button2");
            const badge = document.querySelector(".badge");
            if (!badge) return;

            const current = parseInt((badge.textContent || "0").trim(), 10);
            badge.style.display = current > 0 ? "inline-flex" : "none";

            if (button2) {
                button2.onclick = function () {
                    let badgeValue = parseInt((badge.textContent || "0").trim(), 10);
                    badgeValue++;
                    badge.textContent = badgeValue;
                    badge.style.display = badgeValue > 0 ? "inline-flex" : "none";
                }
            }
        });
    </script>

    <style>
        /* Ideal mover a header.css/theme.css. Lo dejo por compatibilidad inmediata */
        .profile-icon { margin-left: 9.5px; }

        .badge{
            position: absolute;
            top: -6px;
            right: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            background-color: var(--accent, rgb(197, 145, 0));
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid rgba(255,255,255,0.22);
            box-shadow: 0 2px 10px rgba(0,0,0,0.18);
        }

        header { isolation: isolate; }
        .layout { z-index: 99999999; }
    </style>
</head>

<body>

<?php
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webShopFSI";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT COUNT(DISTINCT productID) AS count FROM shoppingCart WHERE userID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cartCount = $row['count'];

    $stmt->close();
    $conn->close();
}
?>

<header>
    <nav class="navbar sticky-top navbar-expand-md navbar-dark bg-dark mb-4">
        <div class="container-fluid">

            <!-- logo -->
            <div class="col d-flex justify-content-center">
                <a class="navbar-brand" href="homepage.php">
                    <img src="../assets/images/fsi_logo_header.png" alt="FSI Reutlingen" width="169px" height="87px">
                </a>
            </div>

            <!-- search bar -->
            <div class="col justify-content-start">
                <form class="d-flex justify-content-center" role="search" action="search.php" method="get">
                    <input class="form-control me-2" name="search" type="search" placeholder="Produktsuche..."
                        aria-label="Search">
                    <button class="btn btn-outline-warning" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- right aligned nav buttons -->
            <div class="col d-flex justify-content-center">
                <ul class="nav justify-content-end nav-underline"
                    style="<?php echo isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true ? ' ' : ''; ?>">
                    <li class="nav-item">
                        <a class="nav-link px-2 text-white" href="products.php">
                            <div class="d-flex flex-column align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-shop" viewBox="0 0 16 16">
                                    <path
                                        d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z" />
                                </svg>
                            </div>
                            <div>Artikelübersicht</div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-2 text-white" href="favorits.php">
                            <div class="d-flex flex-column align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-heart" viewBox="0 0 16 16">
                                    <path
                                        d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                                </svg>
                            </div>
                            <div>Favoriten</div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-2 text-white position-relative" href="warenkorb.php">
                            <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true): ?>
                                <span class="badge"><?php echo $cartCount ?? 0; ?></span>
                            <?php endif; ?>
                            <div class="d-flex flex-column align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-cart3" viewBox="0 0 16 16">
                                    <path
                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                </svg>
                            </div>
                            <div>Warenkorb</div>
                        </a>
                    </li>

                    <div style="margin-top:-4px">
                        <li class="nav-item dropdown">
                            <a class="nav-link px-2 text-white dropdown-toggle" data-bs-toggle="dropdown" href="#"
                                role="button" aria-expanded="false">
                                <svg class="profile-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg>
                                <br>Profil
                            </a>

                            <ul class="dropdown-menu bg-dark layout">
                                <div class="d-flex flex-column align-items-center">
                                    <?php if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true): ?>
                                        <div>
                                            <p style="text-align:center; padding-left:5px;">
                                                <a href="login.php">
                                                    <button type="button" class="btn btn-outline-light me-2">Login</button>
                                                </a>
                                            </p>
                                            <p style="text-align:center;">
                                                <a href="signUp.php">
                                                    <button type="button" class="btn btn-warning">Sign-up</button>
                                                </a>
                                            </p>
                                        </div>
                                    <?php else: ?>
                                        <div style="padding-left: 5px;">
                                            <p style="text-align:center;">
                                                <a href="profil.php">
                                                    <button type="button" class="btn btn-outline-light me-2">Profil</button>
                                                </a>
                                            </p>
                                            <p style="text-align:center;">
                                                <a href="../services/userProvider/logout.php">
                                                    <button type="submit" class="btn btn-warning">Logout</button>
                                                </a>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </ul>
                        </li>
                    </div>

                </ul>
            </div>
        </div>
    </nav>
</header>
