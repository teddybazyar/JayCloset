<?php
require_once "database.php";

function getDataFromSQL($sql, $params=null) {
    global $servername, $database, $username, $password, $dbport;
    try {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        $charset = 'utf8mb4';
        $conn = new PDO("mysql:host=$servername;dbname=$database;charset=$charset;port=$dbport;", $username, $password, $options);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    // Set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $valuesArray = $stmt->fetchAll();
    return $valuesArray;
}

function executeSQL($sql, $params=null) {
    global $servername, $database, $username, $password, $dbport;
    try {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        $charset = 'utf8mb4';
        $conn = new PDO("mysql:host=$servername;dbname=$database;charset=$charset;port=$dbport;", $username, $password, $options);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    $queries = explode(";",$sql);
    foreach($queries as $query) {
        $stmt=$conn->prepare($query);
        $stmt->execute();
        // Set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $valuesArray = $stmt->fetchAll();
    }
    return $valuesArray;
}