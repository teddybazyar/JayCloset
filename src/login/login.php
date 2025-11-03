<?php
require_once "includes/database_functions.php";

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
session_start();

$sql = "SELECT * FROM users where ID = :u and passwrd = :p;";
$u = $_POST["ID"];
$p = $_POST["passwrd"];
$params = [":u"=>$u, ":p"=>$p];


$result = getDataFromSQL($sql, $params);

if (is_array($result) && count ($result) > 0) {
    $_SESSION["LoginStatus"]="YES";
    $_SESSION["UserID"]=$result[0]["userID"];
    $_SESSION["Name"]=$result[0]["firstName"]." ".$result[0]["lastName"];
    $_SESSION["email"]=$result[0]["email"];
    $_SESSION["ADMIN"]=$result[0]["admin"];
}else{
    echo "not correct password";
    $_SESSION["LoginStatus"]="NO";
    $_SESSION["UserID"]="";
    $_SESSION["Name"]="";
    $_SESSION["email"]="";

    header("Location: ../index.php");
    exit;
}
?>