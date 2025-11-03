<?php

    require_once "db_cred.php";

    $connection = new mysqli($host, $dbuser, $dbPswd, $database);

    // Check if the connection was successful
    if ($connection->connect_error) {
        die("Connection failed: ".$connection->connect_error);
    }

?>