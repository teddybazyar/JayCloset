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
require_once "../includes/image_helper.php";

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Invalid security token. Please try again.";
    header("Location: add_item.php");
    exit;
}

// Get and validate input
$itemID = isset($_POST['itemID']) ? trim($_POST['itemID']) : null;
$description = isset($_POST['description']) ? trim($_POST['description']) : null;
$color = isset($_POST['color']) ? trim($_POST['color']) : null;
$size = isset($_POST['size']) ? trim($_POST['size']) : null;
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : null;
$categories = isset($_POST['categories']) ? trim($_POST['categories']) : null;

// Validate all required fields
if (!$itemID || !$description || !$color || !$size || !$gender || !$categories) {
    $_SESSION['error_message'] = "All fields are required.";
    header("Location: add_item.php");
    exit;
}

// Validate itemID format
if (!preg_match('/^[A-Za-z0-9\-_]{1,50}$/', $itemID)) {
    $_SESSION['error_message'] = "Item ID can only contain letters, numbers, hyphens, and underscores.";
    header("Location: add_item.php");
    exit;
}

// Check if itemID already exists
$checkSql = "SELECT itemID FROM descript WHERE itemID = :id";
$checkParams = [":id" => $itemID];
$existing = jayclosetdb::getDataFromSQL($checkSql, $checkParams);

if (is_array($existing) && count($existing) > 0) {
    $_SESSION['error_message'] = "Item ID already exists. Please use a different ID.";
    header("Location: add_item.php");
    exit;
}

// Insert item into database
$insertSql = "INSERT INTO descript (itemID, description_of_item, color, size, gender, categories, reserved) 
              VALUES (:id, :desc, :color, :size, :gender, :cat, 0)";
$insertParams = [
    ":id" => $itemID,
    ":desc" => $description,
    ":color" => $color,
    ":size" => $size,
    ":gender" => $gender,
    ":cat" => $categories
];

try {
    jayclosetdb::executeSQL($insertSql, $insertParams);
    
    // Handle image uploads if any using helper function
    if (isset($_FILES['itemImages']) && !empty($_FILES['itemImages']['name'][0])) {
        $uploadResult = processItemImages($_FILES['itemImages'], $itemID, 1, 5);
        
        if ($uploadResult['success'] > 0) {
            $_SESSION['success_message'] = "Item added successfully with " . $uploadResult['success'] . " image(s)!";
            
            // Add any errors to the message if some uploads failed
            if (!empty($uploadResult['errors'])) {
                $_SESSION['success_message'] .= " Note: " . implode(", ", $uploadResult['errors']);
            }
        } else {
            $_SESSION['success_message'] = "Item added successfully but images failed to upload. Please add images from the edit page.";
            if (!empty($uploadResult['errors'])) {
                $_SESSION['success_message'] .= " Errors: " . implode(", ", $uploadResult['errors']);
            }
        }
    } else {
        $_SESSION['success_message'] = "Item added successfully! Add images from the edit page.";
    }
    
    header("Location: ../closet.php");
    exit;
    
} catch (Exception $e) {
    $_SESSION['error_message'] = "Failed to add item: " . $e->getMessage();
    header("Location: add_item.php");
    exit;
}
?>