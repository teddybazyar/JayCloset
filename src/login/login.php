<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../includes/db_cred.php";
require_once "../includes/database_functions.php";

// Check if session is already started, if not start it with secure settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 when using HTTPS in production
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    session_destroy();
    session_start();
    $_SESSION["login_error"] = "Invalid security token. Please try again.";
    header("Location: ../index.php?page=login");
    exit;
}

// Input validation and sanitization
$userID = trim($_POST["ID"]);
$password = $_POST["passwrd"];

// Validate input
if (empty($userID) || empty($password)) {
    session_destroy();
    session_start();
    $_SESSION["login_error"] = "Please enter both Student ID and password.";
    header("Location: ../index.php?page=login");
    exit;
}

// Query database using prepared statement
$sql = "SELECT * FROM users WHERE ID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Verify password
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $stored_password = $user["passwrd"];
    
    // Hash the input password with MD5 and salts
    $hashed_input = md5($salt1 . $password . $salt2);
    
    // Check if passwords match
    if ($hashed_input === $stored_password) {
        // Successful login - regenerate session ID to prevent session fixation
        session_regenerate_id(true);
        
        // Clear failed login attempts
        unset($_SESSION['login_attempts']);
        unset($_SESSION['last_attempt']);
        
        // Set session variables
        $_SESSION["LoginStatus"] = "YES";
        $_SESSION["UserID"] = $user["ID"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["ADMIN"] = $user["isadmin"];
        $_SESSION["last_activity"] = time(); // For session timeout
        
        // Redirect based on admin status
        if ($_SESSION["ADMIN"] == 1) {
            header("Location: ../admin/admin.php");
        } else {
            header("Location: ../index.php");
        }
        exit;
    }
}

// Failed login
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
$_SESSION['login_attempts']++;
$_SESSION['last_attempt'] = time();

// Clear session and set error
session_destroy();
session_start();
$_SESSION["login_error"] = "Incorrect Student ID or password.";

// Redirect back to login
header("Location: ../index.php?page=login");
exit;
?>