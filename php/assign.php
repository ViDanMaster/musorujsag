<?php
session_start();
include('functions.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assignSubmit'])) {
    $showName = $_POST['selectedShow'];
    $episodeNumber = $_POST['selectedEpisode'];
    $performerNames = $_POST['selectedPerformers'];
    $channelName = $_POST['selectedChannel'];
    $airDatetime = $_POST['airDatetime'];

    $success = assignPerformersToShow($showName, $episodeNumber, $performerNames) && assignShowToChannel($showName, $episodeNumber, $channelName, $airDatetime);

    if ($success) {
        $successMessage = "A műsor sikeresen hozzá lett rendelve a kiválasztott szereplőkhöz, csatornához és időponthoz.";
    } else {
        $errorMessage = "Hiba történt a műsor hozzárendelése során.";
    }
}

$episodes = getShowListWithQuery("SELECT DISTINCT episode_number FROM Episode");
$shows = getShowListWithQuery("SELECT DISTINCT name FROM Episode");
$channels = getChannelListWithQuery("SELECT * FROM Channel");
$performers = getPerformerListWithQuery("SELECT name FROM Performer");
?>

<!DOCTYPE html>
<html lang="en">
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
    <div class="welcome">
        <h2>Üdvözöljük, <?php echo $_SESSION['username']; ?>!</h2>
    </div>
    <div class="form-container">
    <h2>Műsorhoz szereplő, csatorna és időpont hozzárendelése</h2>
    <?php
    if (isset($successMessage)) {
        echo '<p class="success-message">' . $successMessage . '</p>';
    }
    if (isset($errorMessage)) {
        echo '<p class="error-message">' . $errorMessage . '</p>';
    }
    ?>
        <form method="post" action="assign.php" class="form-group-vertical">
            <label for="selectedShow" class="form-label">Válasszon egy műsort:</label>
            <select name="selectedShow" id="selectedShow" required class="form-select">
                <option value='' disabled selected>-</option>
                <?php
                foreach ($shows as $show) {
                    echo "<option value='{$show['name']}'>{$show['name']}</option>";
                }
                ?>
            </select>

            <label for="selectedEpisode" class="form-label">Válasszon egy epizódot:</label>
            <select name="selectedEpisode" id="selectedEpisode" required class="form-select">
                <option value='' disabled selected>-</option>
                <?php
                foreach ($episodes as $episode) {
                    echo "<option value='{$episode['episode_number']}'>{$episode['episode_number']}</option>";
                }
                ?>
            </select>

            <label for="selectedPerformers[]" class="form-label">Válasszon szereplő(ke)t:</label>
            <select name="selectedPerformers[]" id="selectedPerformers[]" multiple required class="form-select">
                <option value='' disabled selected>-</option>
                <?php
                foreach ($performers as $performer) {
                    echo "<option value='{$performer['name']}'>{$performer['name']}</option>";
                }
                ?>
            </select>

            <label for="selectedChannel" class="form-label">Válasszon egy csatornát:</label>
            <select name="selectedChannel" id="selectedChannel" required class="form-select">
                <option value='' disabled selected>Válasszon egy csatornát</option>
                <?php
                foreach ($channels as $channel) {
                    echo "<option value='{$channel['name']}'>{$channel['name']}</option>";
                }
                ?>
            </select>

            <label for="airDatetime" class="form-label">Válasszon egy időpontot (éééé-hh-nn óó:pp):</label>
            <input type="datetime-local" name="airDatetime" id="airDatetime" required class="form-select">

            <button type="submit" name="assignSubmit" class="form-button">Hozzárendelés</button>
        </form>
</main>
</body>
</html>
