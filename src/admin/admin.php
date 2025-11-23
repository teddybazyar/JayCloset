<?php
// Check if session is already started, if not start it with secure settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 when using HTTPS in production
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

// Include navbar and footer classes
require_once "../includes/Navbar.php";
require_once "../includes/Footer.php";

// Custom navbar links for admin
$adminNavLinks = [
    'Admin' => ['url' => 'admin.php', 'icon' => 'fas fa-home'],
    'Closet' => ['url' => '../closet.php', 'icon' => 'fa fa-list'],
    'Users' => ['url' => 'users.php', 'icon' => 'fas fa-users'],
    'Logout' => ['url' => '../login/logout.php', 'icon' => 'fas fa-sign-out-alt']
];

$navbar = new Navbar($adminNavLinks);
$footer = new Footer("Jay's Closet - Admin Dashboard");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Jay's Closet</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php $navbar->display(); ?>

    <div class="h1">
        <h1>Admin Dashboard</h1>
    </div>
    
    <div class="p">
        
        <div style="margin-top: 2rem;">
            <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; margin-top: 1.5rem;">
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); text-align: center; min-width: 250px; transition: transform 0.2s ease;">
                    <a href="users.php" style="text-decoration: none; color: #0984e3; font-size: 1.2rem; font-weight: 600;">
                        Manage Users
                    </a>
                    <p style="margin-top: 0.5rem; color: #636e72; font-size: 0.9rem;">View and manage user accounts</p>
                </div>
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); text-align: center; min-width: 250px; transition: transform 0.2s ease;">
                    <a href="../closet.php" style="text-decoration: none; color: #0984e3; font-size: 1.2rem; font-weight: 600;">
                        Closet Inventory
                    </a>
                    <p style="margin-top: 0.5rem; color: #636e72; font-size: 0.9rem;">Edit and manage closet items</p>
                </div>
            </div>
        </div>
    </div>

    <?php echo $footer->render(); ?>

    <script src="../js/hamburger.js" defer></script>
</body>
</html>