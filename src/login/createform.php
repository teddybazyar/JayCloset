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

<!-- Create form fragment (included into src/index.php). Styles are in ../css/style.css scoped to .create-page -->
<main class="sheet create-page">
    <div class="container">
        <h2>Create an Account</h2>
        <div>
            <form action="/JayCloset/src/login/create.php" method="POST">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                
                <label for="id">Student ID:</label>
                <input type="text" id="id" name="id" required maxlength="7" pattern="[0-9]{7}" title="7 digits only" autocomplete="username">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required maxlength="100" autocomplete="email">

                <label for="fname">First name:</label>
                <input type="text" id="fname" name="fname" required maxlength="50" autocomplete="given-name">

                <label for="lname">Last name:</label>
                <input type="text" id="lname" name="lname" required maxlength="50" autocomplete="family-name">

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="8" maxlength="20" autocomplete="new-password">
                
                <div class="password-requirements">
                    <strong>Password must contain:</strong>
                    <ul>
                        <li>8-20 characters</li>
                        <li>One uppercase letter</li>
                        <li>One lowercase letter</li>
                        <li>One number</li>
                        <li>One special character</li>
                    </ul>
                </div>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8" maxlength="20" autocomplete="new-password">

                <button type="submit">Create Account</button>
            </form>
        </div>
        <p>Already have an account? <a href="index.php?page=login">Login here</a></p>
    </div>
</main>