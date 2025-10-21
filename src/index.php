<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/MainPageContent.php';
require_once __DIR__ . '/includes/Footer.php';

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");
$title = 'Welcome to Jay Closet!'; // title for the tab

$page = isset($_GET['page']) ? $_GET['page'] : 'welcome';

switch ($page) {
    case 'welcome':
        $content = MainPageContent::render();
        break;
    case 'login':
        // $content = LoginForm::render();
        $content = "<main><h2>Login page coming soon!</h2></main>";
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
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="stylesheets/navbar.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <link rel="stylesheet" href="stylesheets/index_about.css">

</head>
<body>

<?php 
$nav->display();      // display the navbar
echo $content;        // display the content of the page
echo $footer->render();
?>

</body>
</html>