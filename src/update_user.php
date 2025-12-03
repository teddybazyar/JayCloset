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
	<style>
		/* Enhanced Update User Page Styling */
		
		body {
			background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
		}

		/* Hero Section */
		.update-hero {
			background: linear-gradient(135deg, #0a2240 0%, #004b98 50%, #3db5e6 100%);
			padding: 60px 20px 40px;
			text-align: center;
			color: white;
			position: relative;
			overflow: hidden;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
		}

		.update-hero::before {
			content: '';
			position: absolute;
			top: -50%;
			right: -50%;
			width: 100%;
			height: 100%;
			background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
			animation: float 20s infinite ease-in-out;
		}

		@keyframes float {
			0%, 100% { transform: translate(0, 0) rotate(0deg); }
			50% { transform: translate(-30px, 30px) rotate(180deg); }
		}

		.update-hero h1 {
			font-size: clamp(2rem, 4vw, 3rem);
			font-weight: 800;
			margin: 0;
			text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
			position: relative;
			z-index: 2;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 15px;
		}

		/* Main Container */
		.update-container {
			max-width: 900px;
			margin: -40px auto 80px;
			padding: 0 20px;
			position: relative;
			z-index: 10;
		}

		/* Profile Card */
		.profile-card {
			background: white;
			border-radius: 20px;
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
			overflow: hidden;
			animation: slideUp 0.6s ease;
		}

		@keyframes slideUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		/* Card Header */
		.card-header-section {
			background: linear-gradient(135deg, #B4D1F8 0%, #3db5e6 100%);
			padding: 40px;
			text-align: center;
			position: relative;
		}

		.card-header-section::after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 0;
			right: 0;
			height: 40px;
			background: white;
			border-radius: 50% 50% 0 0 / 100% 100% 0 0;
		}

		.profile-avatar {
			width: 140px;
			height: 140px;
			background: linear-gradient(135deg, #004b98 0%, #0a2240 100%);
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto 20px;
			font-size: 4rem;
			color: white;
			font-weight: 700;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
			border: 6px solid white;
			position: relative;
			z-index: 2;
		}

		.profile-role-badge {
			background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
			color: white;
			padding: 8px 24px;
			border-radius: 25px;
			font-size: 0.9rem;
			font-weight: 600;
			display: inline-block;
			box-shadow: 0 6px 20px rgba(225, 38, 28, 0.3);
			position: relative;
			z-index: 2;
		}

		.profile-role-badge.admin {
			background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
			box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
		}

		/* Card Body */
		.card-body-section {
			padding: 50px 40px 40px;
		}

		.section-title {
			color: #0a2240;
			font-size: 1.6rem;
			font-weight: 700;
			margin-bottom: 30px;
			padding-bottom: 15px;
			border-bottom: 3px solid #e9ecef;
			display: flex;
			align-items: center;
			gap: 12px;
		}

		.section-title i {
			color: #004b98;
		}

		/* Info Grid */
		.info-grid {
			display: grid;
			gap: 25px;
			margin-bottom: 40px;
		}

		.info-item {
			background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
			padding: 20px 25px;
			border-radius: 12px;
			border-left: 5px solid #004b98;
			transition: all 0.3s ease;
		}

		.info-item:hover {
			transform: translateX(5px);
			box-shadow: 0 6px 20px rgba(0, 75, 152, 0.1);
		}

		.info-label {
			display: flex;
			align-items: center;
			gap: 10px;
			color: #6c757d;
			font-size: 0.9rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			margin-bottom: 8px;
		}

		.info-label i {
			color: #004b98;
			font-size: 1rem;
		}

		.info-value {
			color: #0a2240;
			font-size: 1.2rem;
			font-weight: 600;
		}

		/* Notice Box */
		.notice-box {
			background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
			border-left: 5px solid #ffc107;
			padding: 20px 25px;
			border-radius: 12px;
			margin-bottom: 30px;
			display: flex;
			align-items: start;
			gap: 15px;
		}

		.notice-box i {
			color: #f39c12;
			font-size: 1.5rem;
			margin-top: 3px;
		}

		.notice-content {
			flex: 1;
		}

		.notice-content h3 {
			color: #856404;
			font-size: 1.1rem;
			margin: 0 0 8px 0;
			font-weight: 700;
		}

		.notice-content p {
			color: #856404;
			margin: 0;
			line-height: 1.6;
		}

		/* Error Message */
		.error-card {
			background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
			border-left: 5px solid #dc3545;
			padding: 30px;
			border-radius: 12px;
			text-align: center;
		}

		.error-card i {
			font-size: 4rem;
			color: #dc3545;
			margin-bottom: 20px;
		}

		.error-card h3 {
			color: #721c24;
			font-size: 1.5rem;
			margin-bottom: 15px;
		}

		.error-card p {
			color: #721c24;
			font-size: 1rem;
			margin: 10px 0;
		}

		/* Action Buttons */
		.action-buttons {
			display: flex;
			gap: 15px;
			justify-content: center;
			margin-top: 30px;
			flex-wrap: wrap;
		}

		.btn {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			padding: 16px 35px;
			border-radius: 12px;
			font-size: 1.1rem;
			font-weight: 600;
			text-decoration: none;
			transition: all 0.3s ease;
			border: none;
			cursor: pointer;
		}

		.btn-primary {
			background: linear-gradient(135deg, #004b98 0%, #3db5e6 100%);
			color: white;
			box-shadow: 0 8px 25px rgba(0, 75, 152, 0.3);
		}

		.btn-primary:hover {
			transform: translateY(-3px);
			box-shadow: 0 12px 35px rgba(0, 75, 152, 0.4);
		}

		.btn-secondary {
			background: white;
			color: #004b98;
			border: 3px solid #004b98;
		}

		.btn-secondary:hover {
			background: #004b98;
			color: white;
			transform: translateY(-3px);
		}

		.btn-edit {
			background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
			color: white;
			box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
		}

		.btn-edit:hover {
			transform: translateY(-3px);
			box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
		}

		/* Coming Soon Feature */
		.feature-badge {
			display: inline-block;
			background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
			color: white;
			padding: 4px 12px;
			border-radius: 15px;
			font-size: 0.75rem;
			font-weight: 600;
			margin-left: 10px;
			vertical-align: middle;
		}

		/* Responsive Design */
		@media (max-width: 768px) {
			.update-hero {
				padding: 40px 15px 30px;
			}

			.update-container {
				margin: -30px auto 50px;
			}

			.card-body-section {
				padding: 40px 25px 30px;
			}

			.action-buttons {
				flex-direction: column;
			}

			.btn {
				width: 100%;
			}

			.profile-avatar {
				width: 120px;
				height: 120px;
				font-size: 3rem;
			}
		}

		/* Skeleton loader for future edit form */
		.form-skeleton {
			opacity: 0.5;
			pointer-events: none;
		}
	</style>
</head>
<body>
<?php $nav->display(); ?>

<!-- Hero Section -->
<div class="update-hero">
	<h1>
		<i class="fas fa-user-edit"></i>
		View Profile
	</h1>
</div>

<div class="update-container">
	<div class="profile-card">
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
			<!-- Error State -->
			<div class="card-body-section">
				<div class="error-card">
					<i class="fas fa-exclamation-triangle"></i>
					<h3>User Information Not Found</h3>
					<p>We couldn't retrieve your user information.</p>
					<p>Please try logging in again or contact support if the problem persists.</p>
					<div class="action-buttons">
						<a href="profile.php" class="btn btn-primary">
							<i class="fas fa-arrow-left"></i>
							Return to Profile
						</a>
						<a href="login/logout.php" class="btn btn-secondary">
							<i class="fas fa-sign-out-alt"></i>
							Log Out
						</a>
					</div>
				</div>
			</div>
		<?php else: 
			// Get user initials for avatar
			$fname = $user['fname'] ?? '';
			$lname = $user['lname'] ?? '';
			$initials = '';
			if (!empty($fname)) $initials .= strtoupper($fname[0]);
			if (!empty($lname)) $initials .= strtoupper($lname[0]);
			if (empty($initials)) $initials = strtoupper(substr($user['ID'], 0, 2));
			
			$isAdmin = (isset($user['isadmin']) && $user['isadmin'] == 1);
		?>
			<!-- Card Header with Avatar -->
			<div class="card-header-section">
				<div class="profile-avatar">
					<?php echo htmlspecialchars($initials); ?>
				</div>
				<span class="profile-role-badge <?php echo $isAdmin ? 'admin' : ''; ?>">
					<i class="fas fa-<?php echo $isAdmin ? 'shield-alt' : 'user'; ?>"></i>
					<?php echo $isAdmin ? 'Administrator' : 'Student User'; ?>
				</span>
			</div>

			<!-- Card Body with User Information -->
			<div class="card-body-section">
				<h2 class="section-title">
					<i class="fas fa-id-card"></i>
					User Information
				</h2>

				<div class="info-grid">
					<div class="info-item">
						<div class="info-label">
							<i class="fas fa-hashtag"></i>
							Student ID
						</div>
						<div class="info-value"><?php echo htmlspecialchars($user['ID']); ?></div>
					</div>

					<div class="info-item">
						<div class="info-label">
							<i class="fas fa-user"></i>
							First Name
						</div>
						<div class="info-value"><?php echo htmlspecialchars($fname ?: 'Not set'); ?></div>
					</div>

					<div class="info-item">
						<div class="info-label">
							<i class="fas fa-user"></i>
							Last Name
						</div>
						<div class="info-value"><?php echo htmlspecialchars($lname ?: 'Not set'); ?></div>
					</div>

					<div class="info-item">
						<div class="info-label">
							<i class="fas fa-envelope"></i>
							Email Address
						</div>
						<div class="info-value"><?php echo htmlspecialchars($user['email'] ?? 'Not set'); ?></div>
					</div>

					<div class="info-item">
						<div class="info-label">
							<i class="fas fa-user-tag"></i>
							Account Type
						</div>
						<div class="info-value">
							<?php echo $isAdmin ? 'Administrator Account' : 'Student Account'; ?>
						</div>
					</div>
				</div>

				<!-- Notice about editing -->
				<div class="notice-box">
					<i class="fas fa-info-circle"></i>
					<div class="notice-content">
						<h3>Profile Editing Coming Soon <span class="feature-badge">In Development</span></h3>
						<p>The ability to edit your profile information will be available in a future update. For now, you can view your current information above. If you need to make changes, please contact an administrator.</p>
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="action-buttons">
					<a href="profile.php" class="btn btn-primary">
						<i class="fas fa-arrow-left"></i>
						Back to Profile
					</a>
					<!-- Future edit button (disabled for now) -->
					<!-- <button class="btn btn-edit" disabled>
						<i class="fas fa-edit"></i>
						Edit Profile
					</button> -->
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<?= $footer->render() ?>

<script src="js/hamburger.js" defer></script>
</body>
</html>