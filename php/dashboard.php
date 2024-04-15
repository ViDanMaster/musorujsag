<?php
session_start();
include('functions.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balatonakarattyai műsorújság</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar">
        <a class="navbar-logo" href="dashboard.php">Balatonakarattyai Műsorújság</a>
            <ul>
                <li class="nav-item">
                    <a class="nav-link" href="channel.php">Csatornák</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="show.php">Műsorok</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="assign.php">Hozzárendelés</a>
                </li>
            </ul>
        <a class="logout-btn" href="logout.php">Kijelentkezés</a>
    </nav>
    <main>
    <div>
        <h2>Üdvözöljük, <?php echo $_SESSION['username']; ?>!</h2>
    </div>

    </main>
</body>
</body>
</html>