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

// Get user ID from URL
$userId = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$userId) {
    $_SESSION['error_message'] = "Invalid user ID.";
    header("Location: users.php");
    exit;
}

// Prevent admin from removing their own admin privileges
if ($userId === $_SESSION["UserID"]) {
    $_SESSION['error_message'] = "You cannot modify your own admin privileges.";
    header("Location: users.php");
    exit;
}

// Get current user status
$sql = "SELECT ID, fname, lname, isadmin FROM users WHERE ID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "User not found.";
    header("Location: users.php");
    exit;
}

$user = $result->fetch_assoc();
$newAdminStatus = ($user['isadmin'] == 1) ? 0 : 1;

// Update admin status
$updateSql = "UPDATE users SET isadmin = ? WHERE ID = ?";
$updateStmt = $connection->prepare($updateSql);
$updateStmt->bind_param("is", $newAdminStatus, $userId);

if ($updateStmt->execute()) {
    $action = $newAdminStatus == 1 ? "granted to" : "removed from";
    $_SESSION['success_message'] = "Admin privileges " . $action . " " . htmlspecialchars($user['fname'] . " " . $user['lname']) . ".";
} else {
    $_SESSION['error_message'] = "Failed to update admin status: " . $connection->error;
}

header("Location: users.php");
exit;
?>