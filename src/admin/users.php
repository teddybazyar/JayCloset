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

// Include necessary files
require_once "../includes/Navbar.php";
require_once "../includes/Footer.php";
require_once "../includes/db_cred.php";
require_once "../includes/database_functions.php";

// Custom navbar links for admin
$adminNavLinks = [
    'Admin' => ['url' => 'admin.php', 'icon' => 'fas fa-home'],
    'Closet' => ['url' => '../closet.php', 'icon' => 'fa fa-list'],
    'Users' => ['url' => 'users.php', 'icon' => 'fas fa-users'],
    'Logout' => ['url' => '../login/logout.php', 'icon' => 'fas fa-sign-out-alt']
];

$navbar = new Navbar($adminNavLinks);
$footer = new Footer("Jay's Closet - User Management");

// Fetch all users from database
$sql = "SELECT ID, fname, lname, email, isadmin FROM users ORDER BY ID";
$stmt = $connection->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Jay's Closet</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .users-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .message-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #28a745;
        }

        .message-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #dc3545;
        }

        .users-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .users-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th {
            background: #b8d4e8;
            color: #2d3436;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .users-table tr:hover {
            background: #f8f9fa;
        }

        .users-table tr:last-child td {
            border-bottom: none;
        }

        .admin-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .user-badge {
            background: #6c757d;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #b8d4e8;
            color: #2d3436;
        }

        .btn-edit:hover {
            background: #9fc5db;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-toggle-admin {
            background: #ffc107;
            color: #2d3436;
        }

        .btn-toggle-admin:hover {
            background: #e0a800;
        }

        .search-bar {
            margin: 1rem 0;
            padding: 0.75rem;
            width: 100%;
            max-width: 400px;
            border: 2px solid #dfe6e9;
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-bar:focus {
            outline: none;
            border-color: #b8d4e8;
        }

        .stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .stat-card {
            flex: 1;
            min-width: 150px;
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card h3 {
            color: #636e72;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #2d3436;
        }
    </style>
</head>
<body>
    <?php $navbar->display(); ?>

    <div class="h1">
        <h1>User Management</h1>
    </div>

    <div class="users-container">
        <?php
        if (isset($_SESSION["success_message"])) {
            echo '<div class="message-success">' . htmlspecialchars($_SESSION["success_message"]) . '</div>';
            unset($_SESSION["success_message"]);
        }
        
        if (isset($_SESSION["error_message"])) {
            echo '<div class="message-error">' . htmlspecialchars($_SESSION["error_message"]) . '</div>';
            unset($_SESSION["error_message"]);
        }
        ?>

        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?php echo count($users); ?></div>
            </div>
            <div class="stat-card">
                <h3>Administrators</h3>
                <div class="number"><?php echo count(array_filter($users, function($u) { return $u['isadmin'] == 1; })); ?></div>
            </div>
            <div class="stat-card">
                <h3>Regular Users</h3>
                <div class="number"><?php echo count(array_filter($users, function($u) { return $u['isadmin'] == 0; })); ?></div>
            </div>
        </div>

        <input type="text" id="searchInput" class="search-bar" placeholder="Search users by name, email, or ID...">

        <div class="users-table">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['ID']); ?></td>
                            <td><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if ($user['isadmin'] == 1): ?>
                                    <span class="admin-badge">Admin</span>
                                <?php else: ?>
                                    <span class="user-badge">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="action-btn btn-edit" onclick="editUser('<?php echo $user['ID']; ?>')">Edit</button>
                                <button class="action-btn btn-toggle-admin" onclick="toggleAdmin('<?php echo $user['ID']; ?>', <?php echo $user['isadmin']; ?>)">
                                    <?php echo $user['isadmin'] == 1 ? 'Remove Admin' : 'Make Admin'; ?>
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteUser('<?php echo $user['ID']; ?>', '<?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?>')">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php echo $footer->render(); ?>

    <script src="../js/hamburger.js" defer></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            }
        });

        function editUser(userId) {
            window.location.href = 'edit_user.php?id=' + userId;
        }

        function toggleAdmin(userId, isAdmin) {
            const action = isAdmin == 1 ? 'remove admin privileges from' : 'grant admin privileges to';
            if (confirm('Are you sure you want to ' + action + ' this user?')) {
                window.location.href = 'toggle_admin.php?id=' + userId;
            }
        }

        function deleteUser(userId, userName) {
            if (confirm('Are you sure you want to delete user "' + userName + '"? This action cannot be undone.')) {
                window.location.href = 'delete_user.php?id=' + userId;
            }
        }
    </script>
</body>
</html>