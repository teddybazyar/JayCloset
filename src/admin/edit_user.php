<?php
// Check if session is already started, if not start it with secure settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0);
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

require_once "../includes/Navbar.php";
require_once "../includes/Footer.php";
require_once "../includes/db_cred.php";
require_once "../includes/database_functions.php";

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get user ID from URL
$userId = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$userId) {
    $_SESSION['error_message'] = "Invalid user ID.";
    header("Location: users.php");
    exit;
}

// Fetch user data
$sql = "SELECT * FROM users WHERE ID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "User not found.";
    header("Location: users.php");
    exit;
}

$user = $result->fetch_assoc();

// Custom navbar links for admin
$adminNavLinks = [
    'Home' => ['url' => '../index.php', 'icon' => 'fas fa-home'],
    'Closet' => ['url' => '../closet.php', 'icon' => 'fa fa-list'],
    'Users' => ['url' => 'users.php', 'icon' => 'fas fa-users'],
    'Logout' => ['url' => '../login/logout.php', 'icon' => 'fas fa-sign-out-alt']
];

$navbar = new Navbar($adminNavLinks);
$footer = new Footer("Jay's Closet - Edit User");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Jay's Closet</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .edit-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .edit-container h2 {
            margin-bottom: 1.5rem;
            color: #2d3436;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-group input:focus {
            border-color: #b8d4e8;
            box-shadow: 0 0 5px rgba(184, 212, 232, 0.5);
            outline: none;
        }

        .form-group input:disabled {
            background-color: #f1f3f5;
            cursor: not-allowed;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s ease;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: #b8d4e8;
            color: #2d3436;
        }

        .btn-primary:hover {
            background: #9fc5db;
        }

        .btn-secondary {
            background: #636e72;
            color: white;
        }

        .btn-secondary:hover {
            background: #4a5256;
        }

        .info-text {
            color: #636e72;
            font-size: 0.9rem;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php $navbar->display(); ?>

    <div class="h1">
        <h1>Edit User</h1>
    </div>

    <div class="edit-container">
        <h2>Edit User Information</h2>

        <form action="update_user.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['ID']); ?>">

            <div class="form-group">
                <label for="id">Student ID:</label>
                <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($user['ID']); ?>" disabled>
                <p class="info-text">Student ID cannot be changed</p>
            </div>

            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required maxlength="50">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required maxlength="100">
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="users.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <?php echo $footer->render(); ?>

    <script src="../js/hamburger.js" defer></script>
</body>
</html>