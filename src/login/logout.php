<?php
ini_set('display_errors', 1);
error-reporting(E_ALL & ~e_NOTICE);
session_start();
session_destroy();

header("Location: ../index.php");
exit;
?>
