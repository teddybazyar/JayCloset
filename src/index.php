<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

session_start();

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/MainPageContent.php';
require_once __DIR__ . '/includes/Footer.php';

// Check if user is admin and set navbar accordingly
$isAdmin = isset($_SESSION["ADMIN"]) && $_SESSION["ADMIN"] == 1;

if ($isAdmin) {
    $navLinks = [
        'Home' => ['url' => 'index.php', 'icon' => 'fas fa-home'],
        'Closet' => ['url' => 'closet.php', 'icon' => 'fa fa-list'],
        'Users' => ['url' => 'admin/users.php', 'icon' => 'fas fa-users'],
        'Logout' => ['url' => 'login/logout.php', 'icon' => 'fas fa-sign-out-alt']
    ];
    $nav = new Navbar($navLinks);
} else {
    $nav = new Navbar(); // Use default links
}

$footer = new Footer("Jay Closet 2025");
$title = 'Welcome to Jay Closet!'; // title for the tab

$page = isset($_GET['page']) ? $_GET['page'] : 'welcome';

// Handle page content
switch ($page) {
    case 'welcome':
        $content = MainPageContent::render();
        break;
    case 'login':
        ob_start();
        require_once __DIR__ . '/login/loginform.php';
        $content = ob_get_clean();
        break;
    case 'create':
        ob_start();
        require_once __DIR__ . '/login/createform.php';
        $content = ob_get_clean();
        break;
    default:
        $content = MainPageContent::render();
        break;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php 
    $nav->display();      // display the navbar
    echo $content;        // display the content of the page
    echo $footer->render();
?>

<script src="js/hamburger.js" defer></script>
</body>
</html>