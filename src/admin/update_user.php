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
require_once "../includes/database_functions.php";

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Invalid security token. Please try again.";
    header("Location: users.php");
    exit;
}

// Get and validate input
$userId = isset($_POST['user_id']) ? trim($_POST['user_id']) : null;
$firstName = isset($_POST['fname']) ? trim($_POST['fname']) : null;
$lastName = isset($_POST['lname']) ? trim($_POST['lname']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;

// Validate all fields are present
if (!$userId || !$firstName || !$lastName || !$email) {
    $_SESSION['error_message'] = "All fields are required.";
    header("Location: edit_user.php?id=" . urlencode($userId));
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Please enter a valid email address.";
    header("Location: edit_user.php?id=" . urlencode($userId));
    exit;
}

// Validate name fields
if (!preg_match('/^[\p{L}\s\-\']{1,50}$/u', $firstName) || !preg_match('/^[\p{L}\s\-\']{1,50}$/u', $lastName)) {
    $_SESSION['error_message'] = "Names can only contain letters, spaces, hyphens, and apostrophes.";
    header("Location: edit_user.php?id=" . urlencode($userId));
    exit;
}

// Check if email is already used by another user
$checkEmailSql = "SELECT ID FROM users WHERE email = ? AND ID != ?";
$checkStmt = $connection->prepare($checkEmailSql);
$checkStmt->bind_param("ss", $email, $userId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $_SESSION['error_message'] = "Email already in use by another user.";
    header("Location: edit_user.php?id=" . urlencode($userId));
    exit;
}

// Update user information
$updateSql = "UPDATE users SET fname = ?, lname = ?, email = ? WHERE ID = ?";
$updateStmt = $connection->prepare($updateSql);
$updateStmt->bind_param("ssss", $firstName, $lastName, $email, $userId);

if ($updateStmt->execute()) {
    $_SESSION['success_message'] = "User information updated successfully.";
    header("Location: users.php");
} else {
    $_SESSION['error_message'] = "Failed to update user: " . $connection->error;
    header("Location: edit_user.php?id=" . urlencode($userId));
}
exit;
?>