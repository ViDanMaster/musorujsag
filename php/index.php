<?php
include('functions.php');
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
        <a class="navbar-logo" href="index.php">Balatonakarattyai Műsorújság</a>
        <ul>
            <li class="nav-item">
                <a class="nav-link" href="register.php">Admin Regisztráció</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Admin Bejelentkezés</a>
            </li>
        </ul>
    </nav>
<main>
    <div class="form-container">
        <h2>Felhasználó által kiválasztott csatorna adatai</h2>
        <form method="post" action="index.php" class="form-group">
            <div class="div-label">
                <label for="selectedChannel" class="form-label">Válasszon egy csatornát:</label>
            </div>
                <select name="selectedChannel" id="selectedChannel" class="form-select" required>
                <option value='' disabled selected>Egyik sem</option>
                    <?php

                    $channels = getChannelListWithQuery("SELECT * FROM Channel");

                    foreach ($channels as $channel) {
                        echo "<option value='{$channel['name']}'>{$channel['name']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="listChannelData" class="form-button">Listázás</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['listChannelData'])) {
            $selectedChannel = $_POST['selectedChannel'];

            $channelData = getChannelListWithQuery("SELECT * FROM Channel WHERE name = '$selectedChannel'");

            echo '<h3>' . $selectedChannel . ' csatorna adatai:</h3>';
            echo '<table>';
            echo '<tr><th>Név</th><th>Kategória</th><th>Leírás</th></tr>';
            foreach ($channelData as $data) {
                echo "<tr>";
                echo "<td>{$data['name']}</td>";
                echo "<td>{$data['category']}</td>";
                echo "<td>{$data['description']}</td>";
                echo "</tr>";
            }
            echo '</table>';
        }
        ?>
    </div>

    <div class="form-container">
        <h2>Felhasználó által kiválasztott műsor szereplői</h2>
        <form method="post" action="index.php" class="form-group">
            <div class="div-label">
            <label for="selectedShow" class="form-label">Válasszon egy műsort:</label>
            </div>
            <select name="selectedShow" id="selectedShow" class="form-select" required>
            <option value='' disabled selected>Egyik sem</option>
                <?php

                $shows = getShowListWithQuery("SELECT * FROM Episode");

                foreach ($shows as $show) {
                    echo "<option value='{$show['name']}'>{$show['name']}</option>";
                }
                ?>
            </select>
            <button type="submit" name="listPerformers" class="form-button">Listázás</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['listPerformers'])) {
            $selectedShow = $_POST['selectedShow'];

            $performers = getPerformersForShow($selectedShow);

            echo '<h3>Szereplők a(z) "' . $selectedShow . '" műsorban:</h3>';
            echo '<table>';
            echo '<tr><th>Név</th><th>Születési dátum</th><th>Nemzetiség</th><th>Állás</th></tr>';
            foreach ($performers as $performer) {
                echo "<tr>";
                echo "<td>{$performer['name']}</td>";
                echo "<td>{$performer['birth_date']}</td>";
                echo "<td>{$performer['nationality']}</td>";
                echo "<td>{$performer['occupation']}</td>";
                echo "</tr>";
            }
            echo '</table>';
        }
        ?>
    </div>

    <div class="form-container">
        <h2>Kereskedelmi csatornák műsorkínálata</h2>
        <form method="post" action="index.php" class="form-group">
        <div class="div-label">
            <label for="selectedDate" class="form-label">Válasszon egy dátumot:</label>
        </div>
            <input type="date" name="selectedDate" id="selectedDate" class="form-select" required>
            <button type="submit" name="listSchedule" class="form-button">Listázás</button>
        </form>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['listSchedule'])) {
                $selectedDate = $_POST['selectedDate'];

                $schedule = getScheduleForDate($selectedDate);

                echo '<h3>Kereskedelmi kategóriájú csatornák műsorkínálata ' . $selectedDate . ':</h3>';
                
                if (empty($schedule)) {
                    echo '<p>Nincs elérhető műsor a keresett dátumon.</p>';
                } else {
                    echo '<table>';
                    echo '<tr><th>Csatorna neve</th><th>Műsor neve</th><th>Epizód</th><th>Ismertető</th><th>Időpont</th></tr>';
                    foreach ($schedule as $program) {
                        echo "<tr>";
                        echo "<td>{$program['channel_name']}</td>";
                        echo "<td>{$program['show_name']}</td>";
                        echo "<td>{$program['episode_number']}</td>";
                        echo "<td>{$program['summary']}</td>";
                        echo "<td>{$program['air_datetime']}</td>";
                        echo "</tr>";
                    }
                    echo '</table>';
                }
            }
            ?>
    </div>

    <div class="form-container">
        <h2>Csatornák, ahol ma legalább 10 műsort vetítenek:</h2>
        <?php
        $channels = getChannelsWithAtLeast10ProgramsToday();
        if (empty($channels)) {
            echo '<p>Nincs olyan csatorna, ahol ma legalább 10 műsort vetítenek.</p>';
        } else {
            echo '<table>';
            echo '<tr><th>Csatorna neve</th><th>Kategória</th></tr>';
            foreach ($channels as $channel) {
                echo "<tr>";
                echo "<td>{$channel['channel_name']}</td>";
                echo "<td>{$channel['category']}</td>";
                echo "</tr>";
            }
            echo '</table>';
        }
        ?>
    </div>

    <div class="form-container">
        <h2>Legfiatalabb szereplők életkora nemzetiségenként</h2>
        <?php
        $youngestPerformersByNationality = getYoungestPerformersByNationality();
        if ($youngestPerformersByNationality !== false) {
            echo '<table>';
            echo '<tr><th>Név</th><th>Legfiatalabb életkor</th><th>Nemzetiség</th></tr>';
            foreach ($youngestPerformersByNationality as $performer) {
                echo "<tr>";
                echo "<td>{$performer['name']}</td>";
                echo "<td>{$performer['age']} éves</td>";
                echo "<td>{$performer['nationality']}</td>";
                echo "</tr>";
            }
            echo '</table>';
        } else {
            echo '<p>Hiba történt a lekérdezés során.</p>';
        }
        ?>
    </div>
    </main>
</body>
</html>