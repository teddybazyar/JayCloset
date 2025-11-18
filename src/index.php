<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

session_start();

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/MainPageContent.php';
require_once __DIR__ . '/includes/Footer.php';

$nav = new Navbar();
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