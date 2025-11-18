<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    // Secure session settings - only set if session not started yet
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 when using HTTPS in production
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #c62828;
            font-size: 0.95rem;
            display: inline-block;
            max-width: fit-content;
        }
        
        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #2e7d32;
            font-size: 0.95rem;
            display: inline-block;
            max-width: fit-content;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <?php
        // Display error message if exists
        if (isset($_SESSION["login_error"])) {
            echo '<div class="error-message">';
            echo htmlspecialchars($_SESSION["login_error"]);
            echo '</div>';
            unset($_SESSION["login_error"]);
        }
        
        // Display success message if exists (from registration)
        if (isset($_SESSION["registration_success"])) {
            echo '<div class="success-message">';
            echo htmlspecialchars($_SESSION["registration_success"]);
            echo '</div>';
            unset($_SESSION["registration_success"]);
        }
        ?>
        
        <form action="/JayCloset/src/login/login.php" method="POST">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            
            <label for="ID">Student ID:</label>
            <input type="text" id="ID" name="ID" required maxlength="7" pattern="[0-9]{7}" title="7 digits only" autocomplete="username">
            <br>
            <label for="passwrd">Password:</label>
            <input type="password" id="passwrd" name="passwrd" required maxlength="20" autocomplete="current-password">
            <br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="login/createform.php">Register here</a></p>
    </div>
</body>
</html>