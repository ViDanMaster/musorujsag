<?php
include('functions.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $admin = loginUser($email, $password);
    if ($admin) {
        session_start();
        $_SESSION['username'] = $admin['name'];
        $_SESSION['logged_in'] = true;
        header("Location: dashboard.php");
    } else {
        header("Location: login.php?login=failed");
    }
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balatonakarattyai Műsorújság</title>
    <link rel="stylesheet" href="../css/style.css" >
</head>

<body>
    <nav class="navbar">
        <a class="navbar-logo" href="index.php">Balatonakarattyai Műsorújság</a>
        <div>
            <ul>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Admin Regisztráció</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Admin Bejelentkezés</a>
                </li>
            </ul>
        </div>
    </nav>
    <main>
        <div class="form-container">
            <h2>Bejelentkezés</h2>
            <?php
            if (isset($_GET['login']) && $_GET['login'] == 'failed') {
                echo '<p class="error-message">Hibás céges azonosító vagy jelszó.</p>';
            }
            ?>
            <form action="login.php" method="post" class="form-group">
                <div class="form-group">
                    <div class="div-label">
                    <label for="email" class="form-label">Email:</label>
                    </div>
                    <input type="text" class="form-login-input" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <div class="div-label">
                    <label for="password" class="form-label">Jelszó:</label>
                    </div>
                    <input type="password" class="form-login-input" name="password" id="password" required>
                </div>
                <button type="submit" class="form-button">Bejelentkezés</button>
            </form>
        </div>
    </main>
</body>

</html>