<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'YOUR_DB_USERNAME');
define('DB_PASSWORD', 'YOUR_DB_PASSWORD');
define('DB_DATABASE', 'YOUR_DB_DATABASE_NAME');

function getDB() {
    $dbConnection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($dbConnection->connect_error) {
        die("Connection failed: " . $dbConnection->connect_error);
    }
    return $dbConnection;
}

?>
