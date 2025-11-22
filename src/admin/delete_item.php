<?php
// Check if session is already started, if not start it with secure settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0);
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// Session timeout check (30 minutes)
$timeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: ../index.php?page=login");
    exit;
}
$_SESSION['last_activity'] = time();

// Check if user is logged in and is an admin
if (!isset($_SESSION["LoginStatus"]) || $_SESSION["LoginStatus"] != "YES" || !isset($_SESSION["ADMIN"]) || $_SESSION["ADMIN"] != 1) {
    header("Location: ../index.php?page=login");
    exit;
}

require_once "../includes/db_cred.php";
require_once "../includes/jayclosetdb.php";

// Get item ID from URL
$itemID = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$itemID) {
    $_SESSION['error_message'] = "Invalid item ID.";
    header("Location: ../closet.php");
    exit;
}

// Get item info before deletion
$sql = "SELECT * FROM descript WHERE itemID = :id";
$params = [":id" => $itemID];
$items = jayclosetdb::getDataFromSQL($sql, $params);

if (!is_array($items) || count($items) === 0) {
    $_SESSION['error_message'] = "Item not found.";
    header("Location: ../closet.php");
    exit;
}

$item = $items[0];

// Delete item from database
$deleteSql = "DELETE FROM descript WHERE itemID = :id";
$deleteParams = [":id" => $itemID];

try {
    jayclosetdb::executeSQL($deleteSql, $deleteParams);
    
    // Delete associated images directory
    $imageDir = "../images/items/" . $itemID . "/";
    if (is_dir($imageDir)) {
        $files = glob($imageDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        rmdir($imageDir);
    }
    
    $_SESSION['success_message'] = "Item '" . htmlspecialchars($item['description_of_item']) . "' has been deleted successfully.";
    header("Location: ../closet.php");
    exit;
    
} catch (Exception $e) {
    $_SESSION['error_message'] = "Failed to delete item: " . $e->getMessage();
    header("Location: ../closet.php");
    exit;
}
?>