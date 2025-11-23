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

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Invalid security token. Please try again.";
    header("Location: ../closet.php");
    exit;
}

// Get and validate input
$originalItemID = isset($_POST['original_itemID']) ? trim($_POST['original_itemID']) : null;
$description = isset($_POST['description']) ? trim($_POST['description']) : null;
$color = isset($_POST['color']) ? trim($_POST['color']) : null;
$size = isset($_POST['size']) ? trim($_POST['size']) : null;
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : null;
$categories = isset($_POST['categories']) ? trim($_POST['categories']) : null;

// Validate all required fields
if (!$originalItemID || !$description || !$color || !$size || !$gender || !$categories) {
    $_SESSION['error_message'] = "All fields are required.";
    header("Location: edit_item.php?id=" . urlencode($originalItemID));
    exit;
}

// Update item in database
$updateSql = 
    "UPDATE descript SET 
    description_of_item = :desc,
    color = :color,
    size = :size,
    gender = :gender,
    categories = :cat
    WHERE itemID = :id";

$updateParams = [
    ":desc" => $description,
    ":color" => $color,
    ":size" => $size,
    ":gender" => $gender,
    ":cat" => $categories,
    ":id" => $originalItemID
];

try {
    jayclosetdb::executeSQL($updateSql, $updateParams);
    $_SESSION['success_message'] = "Item updated successfully!";
    header("Location: ../closet.php");
    exit;
    
} catch (Exception $e) {
    $_SESSION['error_message'] = "Failed to update item: " . $e->getMessage();
    header("Location: edit_item.php?id=" . urlencode($originalItemID));
    exit;
}
?>