<?php
include('functions.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['init_employees'])) {
        if(dbInit()){
            header("Location: register.php?registration=init_successful");
            exit;
        }else{
            header("Location: register.php?registration=init_failed");
        }
    }else if(isset($_POST['registration_button'])){
        $name = $_POST['name'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $email = $_POST['email'];
    
        if ($password == $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if(isValidEmail($email)){
                if (registerUser($name, $password, $email)) {
                    header("Location: register.php?registration=success");
                } else{
                    header("Location: register.php?registration=failed");
                }
            }else{
                header("Location: register.php?registration=email_mismatch");
            }
        } else {
            header("Location: register.php?registration=password_mismatch");
        }
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
            <h2>Regisztráció</h2>
            <?php
            if (isset($_GET['registration'])) {
                switch ($_GET['registration']) {
                    case 'success':
                        echo '<p class="success-message">Sikeres regisztráció!</p>';
                        break;
                    case 'failed':
                        echo '<p class="error-message">Ez az e-mail már szerepel az adatbázisban.</p>';
                        break;
                    case 'password_mismatch':
                        echo '<p class="error-message">A megadott jelszavak nem egyeznek.</p>';
                        break;
                    case 'email_mismatch':
                        echo '<p class="error-message">A megadott email nem megfelelő.</p>';
                        break;
                    case 'init_successful':
                        echo '<p class="success-message">Az adminok inicializálása sikerült!</p>';
                        break;
                    case 'init_failed':
                        echo '<p class="error-message">Az adminok már inicializálva vannak.</p>';
                        break;
                }
            }
            ?>
            <form action="register.php" method="post" class="form-group-vertical">
                <input type="text" class="form-input" name="name" id="name" placeholder="Felhasználónév" required>
                <input type="password" id="password" class="form-input" name="password" placeholder="Jelszó" required>
                <input type="password" id="confirm_password" class="form-input" name="confirm_password" placeholder="Jelszó újra" required>
                <input type="email" id="email" class="form-input" name="email" placeholder="Email" required>
                <button type="submit" class="form-button" name="registration_button" class="submit_button">Regisztráció</button>
            </form>
        </div>
        <div class="form-container">
            <form action="register.php" method="post" class="form-group">
                <button type="submit" class="form-button" name="init_employees" class="submit_button">Adminok inicializálása</button>
            </form>
        </div>
        </main>
</body>

</html>
