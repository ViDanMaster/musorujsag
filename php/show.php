<?php
session_start();
include('functions.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['addShow'])) {
        $name = $_POST['name'];
        $episode = $_POST['episode'];
        $summary = $_POST['summary'];

        $success = addShow($name, $episode, $summary);

        if ($success) {
            $successMessage = "A műsor sikeresen felvéve.";
        } else {
            $errorMessage = "Hiba történt a műsor felvétele során.";
        }
    } elseif (isset($_POST['saveEdit'])) {
        $oldShowName = $_POST['oldShowName'];
        $newName = $_POST['newName'];
        $newEpisode = $_POST['newEpisode'];
        $newSummary = $_POST['newSummary'];

        $success = editShow($oldShowName, $newName, $newEpisode, $newSummary);

        if ($success) {
            $successMessage = "A műsor sikeresen módosítva.";
        } else {
            $errorMessage = "Hiba történt a műsor ismertetőjének módosítása során.";
        }
    } elseif (isset($_POST['deleteShow'])) {
        $name = $_POST['oldShowName'];

        $success = deleteShow($name);

        if ($success) {
            $successMessage = "A műsor sikeresen törölve.";
        } else {
            $errorMessage = "Hiba történt a műsor törlése során.";
        }
    }
}

$shows = getShowListWithQuery("SELECT * FROM Episode");

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balatonakarattyai Műsorújság</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function toggleShowList(cell) {
            var row = cell.parentNode.parentNode;
            var employeeCell = row.nextElementSibling;

            if (employeeCell.style.display === 'none' || employeeCell.style.display === '') {
                employeeCell.style.display = 'table-row';
            } else {
                employeeCell.style.display = 'none';
            }
        }



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
            <h2>Új műsor felvétele</h2>
            <form method="post" action="show.php" class="form-group">
                <div class="div-label">
                <label for="name" class="form-label">Műsor neve:</label>
                </div>
                <input type="text" name="name" class="form-input" required>
                <div class="div-label">
                <label for="episode" class="form-label">Epizód:</label>
                </div>
                <input type="number" name="episode" class="form-input">
                <div class="div-label">
                <label for="summary" class="form-label">Ismertető:</label>
                </div>
                <textarea name="summary" rows="2" cols="20"></textarea>
                <button type="submit" class="form-button" name="addShow">Felvétel</button>
            </form>
    </div>

    <div class="form-container">
    <h3>Műsorok kezelése</h3>
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
            <th>Műsor neve</th>
            <th>Epizód</th>
            <th>Ismertető</th>
            <th class="muveletek">Műveletek</th>
        </tr>
        <?php foreach ($shows as $show) : ?>
            <tr>
                <?php if (isset($_POST['editShow']) && $_POST['oldShowName'] === $show['name']) : ?>
                    <form method="post" action="show.php">
                        <td><input type="text" name="newName" class="form-input" value="<?php echo $show['name']; ?>" required></td>
                        <td><input type="text" name="newEpisode" class="form-input" value="<?php echo $show['episode_number']; ?>"></td>
                        <td><textarea name="newSummary" rows="2" cols="10"><?php echo $show['summary']; ?></textarea></td>
                        <td><input type="hidden" name="oldShowName" value="<?php echo $show['name']; ?>"></td>
                        <td>
                            <button type="submit" name="saveEdit" class="table-button-left">Mentés</button>
                            <button type="button" class="table-button-right" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>'">Mégse</button>
                        </td>
                    </form>
                <?php else : ?>
                    <td><a href="javascript:void(0);" class="table-a" onclick="toggleShowList(this)"><?php echo $show['name']; ?></a></td>
                    <td><?php echo $show['episode_number']; ?></td>
                    <td><?php echo $show['summary']; ?></td>
                    <td>
                        <form method="post" action="show.php">
                            <input type="hidden" name="oldShowName" value="<?php echo $show['name']; ?>">
                            <button type="submit" class="table-button-left" name="editShow">Módosítás</button>
                            <button type="submit" class="table-button-right" name="deleteShow" onclick="return confirmDelete()">Törlés</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
            <tr class="projection-list" style="display: none;">
                <td colspan="4">
                    <div class="form-container">
                    <h2>Mikor és hol játsszák?</h2>
                    <table class="projection-table">
                        <tr>
                            <th>Csatorna neve</th>
                            <th>Időpont</th>
                        </tr>
                        <?php
                        $projections = getProjectionsForShow($show['name'], $show['episode_number']);
                        foreach ($projections as $projection) : ?>
                            <tr>
                                <td><?php echo $projection['channel_name']; ?></td>
                                <td><?php echo $projection['air_datetime']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</main>
</body>

</html>
