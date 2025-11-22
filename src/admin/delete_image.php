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

// Get parameters
$itemID = isset($_GET['id']) ? trim($_GET['id']) : null;
$imageNum = isset($_GET['img']) ? intval($_GET['img']) : null;

if (!$itemID || !$imageNum) {
    $_SESSION['error_message'] = "Invalid parameters.";
    header("Location: ../closet.php");
    exit;
}

// Build image path
$imagePath = "../images/items/" . $itemID . "/" . $itemID . "-" . $imageNum . ".png";
// Should I allow additional images? Like .jpg, etc.

// Check if file exists and delete it
if (file_exists($imagePath)) {
    if (unlink($imagePath)) {
        $_SESSION['success_message'] = "Image deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete image file.";
    }
} else {
    $_SESSION['error_message'] = "Image file not found.";
}

// Redirect back to edit page
header("Location: edit_item.php?id=" . urlencode($itemID));
exit;
?>