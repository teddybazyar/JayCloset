<?php
// Secure session settings
ini_set('session.cookie', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Session timeout check (30 minutes)
$timeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destory();
    header("Location: ../index.php?page=login");
    exit;
}
$_SESSION['last_activity'] = time();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["LoginStatus"]) || $_SESSION["LoginStatus"] != "YES" || !isset($_SESSION["ADMIN"]) || $_SESSION["ADMIN"] != 1) {
    header("Location: ../index.php?page=login");
    exit;
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard - Jay's Closet</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <div class="h1">
            <h1>Admin Dashboard</h1>
        </div>

        <div class="p">
            <h2>Coming Soon</h2>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION["email"]); ?>!</p>
            <p>The admin dashboard is currently under construction. Check back soon for management features.</p>
        </div>
    </body>
</html>