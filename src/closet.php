<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");
$title = 'Closet - Jay Closet';

$content = '<main id="closet"><h1>Closet</h1><p>Closet page is coming soon!</p></main>';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo htmlspecialchars($title); ?></title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
$nav->display();
echo $content;
echo $footer->render();
?>

</body>
</html>