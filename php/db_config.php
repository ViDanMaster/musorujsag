<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'musorujsag');
define('DB_PASSWORD', 'erosjelszo');
define('DB_DATABASE', 'musorujsag');

function getDB() {
    $dbConnection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($dbConnection->connect_error) {
        die("Connection failed: " . $dbConnection->connect_error);
    }
    return $dbConnection;
}

?>