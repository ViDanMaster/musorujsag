<?php
include('db_config.php');

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function dbInit() {
    try {
        $adminsData = [
            ['admin1', 'password1', 'admin1@example.com'],
            ['admin2', 'password2', 'admin2@example.com'],
            ['admin3', 'password3', 'admin3@example.com'],
            ['admin4', 'password4', 'admin4@example.com'],
            ['admin5', 'password5', 'admin5@example.com'],
            ['admin6', 'password6', 'admin6@example.com'],
            ['admin7', 'password7', 'admin7@example.com'],
            ['admin8', 'password8', 'admin8@example.com'],
            ['admin9', 'password9', 'admin9@example.com'],
            ['admin10', 'password10', 'admin10@example.com']
        ];

        foreach ($adminsData as $data) {
            $name = $data[0];
            $password = password_hash($data[1], PASSWORD_DEFAULT);
            $email = $data[2];

            $db = getDB();
            $stmt = $db->prepare("INSERT INTO Admin (name, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $password, $email);
            $stmt->execute();
            $stmt->close();
            $db->close();
        }

        return true;
    } catch (Exception $e) {
        echo "Hiba történt az adatbázis inicializálásakor: " . $e->getMessage();
        return false;
    }
}

function registerUser($name, $password, $email) {
    try {
        $db = getDB();
        $data = $db->prepare("INSERT INTO Admin (name, password, email) VALUES (?, ?, ?)");
        $data->bind_param("sss", $name, password_hash($password, PASSWORD_DEFAULT), $email);
        $result = $data->execute();
        $data->close();
        $db->close();
        return $result;
    } catch (Exception $e) {
        return null;
    }
}

function loginUser($email, $password) {
    $db = getDB();
    $data = $db->prepare("SELECT * FROM Admin WHERE email = ?");
    $data->bind_param("s", $email);
    $data->execute();
    $result = $data->get_result()->fetch_assoc();
    $data->close();
    $db->close();

    if ($result && password_verify($password, $result['password'])) {
        return $result;
    } else {
        return null;
    }
}

function getChannelListWithQuery($query) {
    $db = getDB();
    $result = $db->query($query);
    $channels = [];
    while ($row = $result->fetch_assoc()) {
        $channels[] = $row;
    }
    $result->close();
    $db->close();
    return $channels;
}

function getShowListWithQuery($query) {
    $db = getDB();
    $result = $db->query($query);
    $shows = [];
    while ($row = $result->fetch_assoc()) {
        $shows[] = $row;
    }
    $result->close();
    $db->close();
    return $shows;
}

function getPerformerListWithQuery($query) {
    $db = getDB();
    $result = $db->query($query);
    $performers = [];
    while ($row = $result->fetch_assoc()) {
        $performers[] = $row;
    }
    $result->close();
    $db->close();
    return $performers;
}

function getShowsForChannel($channelName) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Episode WHERE show_name IN (SELECT show_name FROM Projection WHERE channel_name = ?)");
    $stmt->bind_param("s", $channelName);
    $stmt->execute();
    $result = $stmt->get_result();
    $shows = [];
    while ($row = $result->fetch_assoc()) {
        $shows[] = $row;
    }
    $stmt->close();
    $db->close();
    return $shows;
}

function getEpisodesForShow($showName) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Episode WHERE name = ?");
    $stmt->bind_param("s", $showName);
    $stmt->execute();
    $result = $stmt->get_result();
    $episodes = [];
    while ($row = $result->fetch_assoc()) {
        $episodes[] = $row;
    }
    $stmt->close();
    $db->close();
    return $episodes;
}

function getProjectionsForShow($showName, $episodeNumber) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM Projection WHERE show_name = ? AND episode_number = ?");
        $stmt->bind_param("ss", $showName, $episodeNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $projections = [];
        while ($row = $result->fetch_assoc()) {
            $projections[] = $row;
        }
        $stmt->close();
        $db->close();
        return $projections;
    } catch (Exception $e) {
        return false;
    }
}

function getPerformersForShow($showName) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Performer.* FROM Performer JOIN Show_Performer ON Performer.name = Show_Performer.performer_name WHERE Show_Performer.show_name = ? ORDER BY Performer.name ASC");
    $stmt->bind_param("s", $showName);
    $stmt->execute();
    $result = $stmt->get_result();
    $performers = [];
    while ($row = $result->fetch_assoc()) {
        $performers[] = $row;
    }
    $stmt->close();
    $db->close();
    return $performers;
}

function getScheduleForDate($selectedDate) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT Channel.name AS channel_name, Episode.name AS show_name, Episode.episode_number, Episode.summary, Projection.air_datetime 
            FROM Channel
            INNER JOIN Projection ON Channel.name = Projection.channel_name
            INNER JOIN Episode ON Projection.show_name = Episode.name AND Projection.episode_number = Episode.episode_number
            WHERE DATE(Projection.air_datetime) = ? AND Channel.category = 'Kereskedelmi'
            ORDER BY Projection.air_datetime");
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $schedule = [];
        while ($row = $result->fetch_assoc()) {
            $schedule[] = $row;
        }
        $stmt->close();
        $db->close();
        return $schedule;
    } catch (Exception $e) {
        return false;
    }
}

function getChannelsWithAtLeast10ProgramsToday(){
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT Channel.name AS channel_name, Channel.category, COUNT(DISTINCT Episode.name) AS show_count
                FROM Channel
                INNER JOIN Projection ON Channel.name = Projection.channel_name
                INNER JOIN Episode ON Projection.show_name = Episode.name AND Projection.episode_number = Episode.episode_number
                WHERE DATE(Projection.air_datetime) = CURDATE()
                HAVING show_count >= 10");
        $stmt->execute();
        $result = $stmt->get_result();
        $schedule = [];
        while ($row = $result->fetch_assoc()) {
            $schedule[] = $row;
        }
        $stmt->close();
        $db->close();
        return $schedule;
    } catch (Exception $e) {
        return false;
    }
}

function getYoungestPerformersByNationality() {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT nationality, name, MIN(birth_date) AS youngest_birth_date,
            FLOOR(DATEDIFF(CURDATE(), MIN(birth_date))/365) AS age
            FROM Performer
            GROUP BY nationality");
        $stmt->execute();
        $result = $stmt->get_result();
        $performers = [];
        while ($row = $result->fetch_assoc()) {
            $performers[] = $row;
        }
        $stmt->close();
        $db->close();
        return $performers;
    } catch (Exception $e) {
        return false;
    }
}


function deleteChannel($name) {
    try {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM Channel WHERE name = ?");
        $stmt->bind_param("s", $name);
        $result = $stmt->execute();
        $stmt->close();
        $db->close();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function editChannel($oldName, $newName, $category, $description) {
    try {
        $db = getDB();

        $stmt = $db->prepare("UPDATE Channel SET name = ?, category = ?, description = ? WHERE name = ?");
        $stmt->bind_param("ssss", $newName, $category, $description, $oldName);
        $result = $stmt->execute();
        $stmt->close();

        $db->close();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function addChannel($name, $category, $description) {
    try {
        $db = getDB();
        $data = $db->prepare("INSERT INTO Channel (name, category, description) VALUES (?, ?, ?)");
        $data->bind_param("sss", $name, $category, $description);
        $result = $data->execute();
        $data->close();
        $db->close();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function deleteShow($name) {
    try {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM Episode WHERE show_name = ?");
        $stmt->bind_param("s", $name);
        $result = $stmt->execute();
        $stmt->close();
        $db->close();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function editShow($oldName, $newName, $episode, $summary) {
    try {
        $db = getDB();

        $stmt = $db->prepare("UPDATE Episode SET show_name = ?, episode_number = ?, summary = ? WHERE show_name = ?");
        $stmt->bind_param("ssss", $newName, $episode, $summary, $oldName);
        $result = $stmt->execute();
        $stmt->close();

        $db->close();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function addShow($name, $episode, $summary) {
    try {
        $db = getDB();
        $data = $db->prepare("INSERT INTO Episode (name, episode_number, summary) VALUES (?, ?, ?)");
        $data->bind_param("sss", $name, $episode, $summary);
        $result = $data->execute();
        $data->close();
        $db->close();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function assignPerformersToShow($showName, $episodeNumber, $performerNames) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Show_Performer (show_name, episode_number, performer_name) VALUES (?, ?, ?)");
        
        foreach ($performerNames as $performerName) {
            $stmt->bind_param("sss", $showName, $episodeNumber, $performerName);
            $stmt->execute();
        }

        $stmt->close();
        $db->close();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function assignShowToChannel($showName, $episodeNumber, $channelName, $airDatetime) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Projection (show_name, episode_number, channel_name, air_datetime) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $showName, $episodeNumber, $channelName, $airDatetime);
        $result = $stmt->execute();
        $stmt->close();
        $db->close();
        return $result;
    } catch (Exception $e) {
        return false;
    }
}
?>
