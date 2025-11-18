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
    echo "<script>
        alert('Invalid security token. Please try again.');
        window.location.href = '../index.php?page=create';
    </script>";
    exit;
}

// Input sanitization and validation
$ID = trim($_POST["id"]);
$email = trim($_POST["email"]);
$firstname = trim($_POST["fname"]);
$lastname = trim($_POST["lname"]);
$rawpassword = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate all required fields are present
if (empty($ID) || empty($email) || empty($firstname) || empty($lastname) || empty($rawpassword) || empty($confirm_password)) {
    echo "<script>
        alert('All fields are required.');
        window.history.back();
    </script>";
    exit;
}

// Validate Student ID format (7 digits only)
if (!preg_match('/^[0-9]{7}$/', $ID)) {
    echo "<script>
        alert('Student ID must be exactly 7 digits.');
        window.history.back();
    </script>";
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
        alert('Please enter a valid email address.');
        window.history.back();
    </script>";
    exit;
}

// Validate name fields (letters, spaces, hyphens, apostrophes, and accented characters)
if (!preg_match('/^[\p{L}\s\-\']{1,50}$/u', $firstname) || !preg_match('/^[\p{L}\s\-\']{1,50}$/u', $lastname)) {
    echo "<script>
        alert('Names can only contain letters, spaces, hyphens, and apostrophes.');
        window.history.back();
    </script>";
    exit;
}

// Check if passwords match
if ($rawpassword !== $confirm_password) {
    echo "<script>
        alert('Passwords do not match.');
        window.history.back();
    </script>";
    exit;
}

// Password requirement variables
$minlength = 8;
$maxlength = 20;

// Check for complex password
if (strlen($rawpassword) < $minlength || strlen($rawpassword) > $maxlength || 
    !preg_match('/[A-Z]/', $rawpassword) || 
    !preg_match('/[a-z]/', $rawpassword) || 
    !preg_match('/[0-9]/', $rawpassword) || 
    !preg_match('/[^A-Za-z0-9]/', $rawpassword)) {
    
    $password_error_message = "Password must contain 8-20 characters, including uppercase, lowercase, number, and special character.";
    echo "<script>
        alert(" . json_encode($password_error_message) . ");
        window.history.back();
    </script>";
    exit;
}

// Check if username already exists
$qry = "SELECT * FROM users WHERE ID = ?";
$stmt = $connection->prepare($qry);
$stmt->bind_param("s", $ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>
        alert('Username already exists. Please choose a different one.');
        window.history.back();
    </script>";
    exit;
}

// Check if email already exists
$email_qry = "SELECT * FROM users WHERE email = ?";
$stmt2 = $connection->prepare($email_qry);
$stmt2->bind_param("s", $email);
$stmt2->execute();
$email_result = $stmt2->get_result();

if ($email_result->num_rows > 0) {
    echo "<script>
        alert('Email already registered. Please use a different email or login.');
        window.history.back();
    </script>";
    exit;
}

// Hash password using MD5 with salts
$hashed_password = md5($salt1 . $rawpassword . $salt2);

// Insert new user into database
$sql = "INSERT INTO users (ID, fname, lname, email, passwrd, isadmin) VALUES (?, ?, ?, ?, ?, 0)";
$stmt3 = $connection->prepare($sql);
$stmt3->bind_param("sssss", $ID, $firstname, $lastname, $email, $hashed_password);

if ($stmt3->execute()) {
    // Set success message
    $_SESSION["registration_success"] = "Account created successfully! Please log in.";
    
    // Redirect to login page
    header("Location: ../index.php?page=login");
    exit;
} else {
    echo "<script>
        alert('Error creating account: " . addslashes($connection->error) . "');
        window.history.back();
    </script>";
    exit;
}
?>