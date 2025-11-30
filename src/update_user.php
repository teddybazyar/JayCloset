<?php
if (session_status() === PHP_SESSION_NONE) {
	ini_set('session.cookie_httponly', 1);
	ini_set('session.cookie_secure', 0); 
	ini_set('session.use_strict_mode', 1);
	session_start();
}

require_once __DIR__ . '/includes/database_functions.php';
require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';
require_once __DIR__ . '/includes/jayclosetdb.php';

if (!isset($_SESSION["LoginStatus"]) || $_SESSION["LoginStatus"] !== "YES") {
	header("Location: ../index.php?page=login");
	exit;
}

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");
$title = 'Update Profile - JayCloset';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo htmlspecialchars($title); ?></title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php $nav->display(); ?>
<main class="sheet create-page">
	<div class="container">
		<h1>View Profile</h1>

		<?php
		$dbUserID = isset($_SESSION['UserID']) ? (int)$_SESSION['UserID'] : 0;

		$user = null;
		if ($dbUserID > 0) {
			try {
				$sql = "SELECT ID, fname, lname, email, isadmin FROM users WHERE ID = ? LIMIT 1";
				$rows = jayclosetdb::getDataFromSQL($sql, [$dbUserID]);
				if (!empty($rows) && isset($rows[0])) {
					$user = $rows[0];
				}
			} catch (Exception $e) {
				$user = null;
			}
		}

		if ($user === null) : ?>
			<div class="message-error">
				<p>User information not found.</p>
				<p><a href="profile.php">Return to profile</a></p>
			</div>
		<?php else: ?>
			<section>
				<h2>User Information</h2>
				<p><strong>Student ID:</strong> <?php echo htmlspecialchars($user['ID']); ?></p>
				<p><strong>First Name:</strong> <?php echo htmlspecialchars($user['fname'] ?? ''); ?></p>
				<p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['lname'] ?? ''); ?></p>
				<p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
				<p><strong>Role:</strong>
					<?php echo (isset($user['isadmin']) && $user['isadmin'] == 1) ? 'Administrator' : 'User'; ?>
				</p>

				<p>Editing is not yet available.</p>
				<p><a href="profile.php">Return to profile</a></p>
			</section>
		<?php endif; ?>
	</div>
</main>

<?= $footer->render() ?>

<script src="js/hamburger.js" defer></script>
</body>
</html>

