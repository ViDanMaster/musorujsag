<?php
session_start();
include('functions.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['addChannel'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $description = $_POST['description'];

        $success = addChannel($name, $category, $description);

        if ($success) {
            $successMessage = "A csatorna sikeresen hozzáadva.";
        } else {
            $errorMessage = "Hiba történt a csatorna hozzáadása során.";
        }
    }elseif (isset($_POST['saveEdit'])) {
        $oldName = $_POST['oldName'];
        $newName = $_POST['newName'];
        $category = $_POST['newCategory'];
        $description = $_POST['newDescription'];

        $success = editChannel($oldName, $newName, $category, $description);

        if ($success) {
            $successMessage = "A csatorna sikeresen módosítva.";
        } else {
            $errorMessage = "Hiba történt a csatorna módosítása során.  $oldName $newName $category $description";
        }
    } elseif (isset($_POST['deleteChannel'])) {
        $name = $_POST['oldName'];

        $success = deleteChannel($name);

        if ($success) {
            $successMessage = "A csatorna sikeresen törölve.";
        } else {
            $errorMessage = "Hiba történt a csatorna törlése során.";
        }
    }
}

$channels = getChannelListWithQuery("SELECT * FROM Channel")

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balatonakarattyai Műsorújság</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
    function confirmDelete() {
            return confirm("Biztosan törölni szeretné ezt a csatornát?");
        }
    </script>
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
    <div class="welcome">
        <h2>Üdvözöljük, <?php echo $_SESSION['username']; ?>!</h2>
    </div>

    <div class="form-container">
            <h2>Új csatorna hozzáadása</h2>
            <form method="post" action="channel.php" class="form-group">
                <div class="div-label">
                <label for="name" class="form-label">Csatorna neve:</label>
                </div>
                <input type="text" name="name" id="name" class="form-input" required>
                <div class="div-label">
                <label for="category" class="form-label">Kategória:</label>
                </div>
                <input type="text" name="category" id="category" class="form-input">
                <div class="div-label">
                <label for="description" class="form-label">Leírás:</label>
                </div>
                <textarea name="description" id="description" rows="2" cols="20"></textarea>
                <button type="submit" name="addChannel" class="form-button">Hozzáadás</button>
            </form>
    </div>

    <div class="form-container">
    <h2>Csatornák kezelése</h2>
    <?php
        if (isset($successMessage)) {
            echo '<p class="success-message">' . $successMessage . '</p>';
        }
        if (isset($errorMessage)) {
            echo '<p class="error-message">' . $errorMessage . '</p>';
        }
        ?>
    <table>
        <tr>
            <th>Csatorna neve</th>
            <th>Kategória</th>
            <th>Leírás</th>
            <th class="muveletek">Műveletek</th>
        </tr>
        <?php foreach ($channels as $channel) : ?>
            <tr>
                <?php if (isset($_POST['editChannel']) && $_POST['oldName'] === $channel['name']) : ?>
                    <form method="post" action="channel.php">
                        <td><input type="text" name="newName" class="table-input" value="<?php echo $channel['name']; ?>" required></td>
                        <td><input type="text" name="newCategory" class="table-input" value="<?php echo $channel['category']; ?>"></td>
                        <td><textarea name="newDescription" rows="2" cols="15"><?php echo $channel['description']; ?></textarea></td>
                        <input type="hidden" name="oldName" value="<?php echo $channel['name']; ?>"></td>
                        <td>
                            <button type="submit" name="saveEdit" class="table-button-left">Mentés</button>
                            <button type="button"  class="table-button-right" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>'">Mégse</button>
                        </td>
                    </form>
                <?php else : ?>
                    <td><?php echo $channel['name']; ?></td>
                    <td><?php echo $channel['category']; ?></td>
                    <td><?php echo $channel['description']; ?></td>
                    <td>
                        <form method="post" action="channel.php">
                            <input type="hidden" name="oldName" value="<?php echo $channel['name']; ?>">
                            <button type="submit" name="editChannel" class="table-button-left">Módosítás</button>
                            <button type="submit" name="deleteChannel" class="table-button-right" onclick="return confirmDelete()">Törlés</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</main>
</body>

</html>
