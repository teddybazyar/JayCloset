<?php
require_once __DIR__ . '/processes/cart.php';

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); 
    ini_set('session.use_strict_mode', 1);
    session_start();
}

require_once __DIR__ . '/includes/database_functions.php';
require_once __DIR__ . '/includes/jayclosetdb.php';
require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';

if (!isset($_SESSION["LoginStatus"]) || $_SESSION["LoginStatus"] !== "YES") {
    header("Location: ../index.php?page=login");
    exit;
}

$userID = isset($_SESSION['UserID']) ? htmlspecialchars($_SESSION['UserID']) : '';
$userEmail = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

$cartItems = [];
if (isset($_SESSION['cart']) && is_object($_SESSION['cart'])) {
    try {
        if (method_exists($_SESSION['cart'], 'getItems')) {
            $cartItems = $_SESSION['cart']->getItems();
        }
    } catch (Exception $e) {
        $cartItems = [];
    }
}

$orderHistory = [];

$dbUserID = isset($_SESSION['UserID']) ? (int)$_SESSION['UserID'] : 0;

$sql = "SELECT orderID, qty, time_placed, expiration, active_order FROM orders WHERE ID = ? ORDER BY time_placed DESC";
try {
    $orderHistory = jayclosetdb::getDataFromSQL($sql, [$dbUserID]);
} catch (Exception $e) {
    $orderHistory = [];
}

$itemsSql = "SELECT i.itemID, i.sku, d.description_of_item, d.color, d.size FROM items i LEFT JOIN descript d ON i.itemID = d.itemID WHERE i.orderID = ?";
if (!empty($orderHistory)) {
    foreach ($orderHistory as &$ord) {
        try {
            $ordItems = jayclosetdb::getDataFromSQL($itemsSql, [(int)$ord['orderID']]);
            $ord['items'] = $ordItems;
        } catch (Exception $e) {
            $ord['items'] = [];
        }
    }
    unset($ord);
}

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");
$title = 'Profile - Jay Closet';
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Enhanced Profile Page Styling */
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        /* Profile Hero Section */
        .profile-hero {
            background: linear-gradient(135deg, #0a2240 0%, #004b98 50%, #3db5e6 100%);
            padding: 60px 20px 40px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .profile-hero::before {
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

        .profile-hero h1 {
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
        .profile-container {
            max-width: 1400px;
            margin: -30px auto 60px;
            padding: 0 20px;
            position: relative;
            z-index: 10;
        }

        /* Profile Grid */
        .profile-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        /* Sidebar Card */
        .sidebar-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            height: fit-content;
            position: sticky;
            top: 140px;
        }

        .user-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #004b98 0%, #3db5e6 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 3rem;
            color: white;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(0, 75, 152, 0.3);
        }

        .user-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .user-info h2 {
            color: #0a2240;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .user-info p {
            color: #6c757d;
            font-size: 0.95rem;
            margin: 8px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .user-info p i {
            color: #004b98;
        }

        .info-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #e9ecef, transparent);
            margin: 25px 0;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #004b98;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #0a2240;
            display: block;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #004b98 0%, #3db5e6 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(0, 75, 152, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 75, 152, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #0a2240;
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #f8f9fa;
            border-color: #004b98;
        }

        .btn-logout {
            background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(225, 38, 28, 0.3);
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(225, 38, 28, 0.4);
        }

        /* Main Content Area */
        .main-content-area {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Section Card */
        .section-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #004b98 0%, #3db5e6 100%);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .section-header h2 {
            color: #0a2240;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .section-header i {
            color: #004b98;
        }

        .badge {
            background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Cart Items */
        .cart-items-list {
            display: grid;
            gap: 15px;
        }

        .cart-item-row {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #004b98;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .cart-item-row:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(0, 75, 152, 0.15);
        }

        .cart-item-row i {
            font-size: 1.5rem;
            color: #004b98;
        }

        .cart-item-row span {
            color: #0a2240;
            font-weight: 500;
            font-size: 1.05rem;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 80px;
            color: #e9ecef;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #495057;
            font-size: 1.4rem;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 25px;
        }

        /* Order Cards */
        .order-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .order-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background: linear-gradient(180deg, #004b98 0%, #3db5e6 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .order-card:hover {
            border-color: #B4D1F8;
            box-shadow: 0 8px 25px rgba(0, 75, 152, 0.15);
        }

        .order-card:hover::before {
            transform: scaleY(1);
        }

        .order-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .order-id {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0a2240;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-active {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .status-completed {
            background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
            color: white;
        }

        .order-meta {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
        }

        .order-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #495057;
            font-size: 0.95rem;
        }

        .order-meta-item i {
            color: #004b98;
        }

        .order-meta-item strong {
            color: #0a2240;
        }

        .order-items-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .order-items-list li {
            padding: 12px 15px;
            background: white;
            margin-bottom: 8px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 3px solid #3db5e6;
        }

        .order-items-list li i {
            color: #3db5e6;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-card {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .profile-hero {
                padding: 40px 15px 30px;
            }

            .profile-container {
                margin: -20px auto 40px;
            }

            .section-card {
                padding: 25px 20px;
            }

            .quick-stats {
                grid-template-columns: 1fr;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-meta {
                flex-direction: column;
                gap: 12px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-card {
            animation: fadeIn 0.6s ease;
        }
    </style>
</head>
<body>
<?php $nav->display(); ?>

<!-- Hero Section -->
<div class="profile-hero">
    <h1>
        <i class="fas fa-user-circle"></i>
        Your Profile
    </h1>
</div>

<div class="profile-container">
    <div class="profile-grid">
        <!-- Sidebar -->
        <aside class="sidebar-card">
            <div class="user-avatar">
                <?php 
                    // Display first letter of student ID or email
                    $initial = !empty($userID) ? strtoupper($userID[0]) : (!empty($userEmail) ? strtoupper($userEmail[0]) : 'U');
                    echo htmlspecialchars($initial);
                ?>
            </div>
            
            <div class="user-info">
                <h2>Student Profile</h2>
                <p><i class="fas fa-id-card"></i> <strong>ID:</strong> <?php echo $userID; ?></p>
                <p><i class="fas fa-envelope"></i> <?php echo $userEmail; ?></p>
            </div>

            <div class="info-divider"></div>

            <div class="quick-stats">
                <div class="stat-box">
                    <span class="stat-number"><?php echo count($cartItems); ?></span>
                    <span class="stat-label">Cart Items</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number"><?php echo count($orderHistory); ?></span>
                    <span class="stat-label">Total Orders</span>
                </div>
            </div>

            <div class="info-divider"></div>

            <div class="action-buttons">
                <a href="update_user.php" class="btn-action btn-primary">
                    <i class="fas fa-user-edit"></i>
                    Edit Profile
                </a>
                <a href="cart_page.php" class="btn-action btn-secondary">
                    <i class="fas fa-shopping-cart"></i>
                    View Cart
                </a>
                <a href="closet.php" class="btn-action btn-secondary">
                    <i class="fas fa-store"></i>
                    Browse Closet
                </a>
                <a href="login/logout.php" class="btn-action btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Log Out
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content-area">
            
            <!-- Cart Section -->
            <section class="section-card">
                <div class="section-header">
                    <h2>
                        <i class="fas fa-shopping-cart"></i>
                        Current Cart
                    </h2>
                    <?php if (!empty($cartItems)): ?>
                        <span class="badge"><?php echo count($cartItems); ?> Items</span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($cartItems)): ?>
                    <div class="cart-items-list">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item-row">
                                <i class="fas fa-check-circle"></i>
                                <span>
                                    <?php
                                    if (is_object($item)) {
                                        if (method_exists($item, 'getTitle')) {
                                            echo htmlspecialchars($item->getTitle());
                                        } elseif (method_exists($item, 'getDescription')) {
                                            echo htmlspecialchars($item->getDescription());
                                        } elseif (property_exists($item, 'description')) {
                                            echo htmlspecialchars($item->description);
                                        } else {
                                            echo 'Item';
                                        }
                                    } elseif (is_array($item)) {
                                        echo htmlspecialchars($item['description_of_item'] ?? $item['description'] ?? $item['title'] ?? 'Item');
                                    } else {
                                        echo htmlspecialchars((string)$item);
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="margin-top: 25px; text-align: center;">
                        <a href="cart_page.php" class="btn-action btn-primary" style="display: inline-flex;">
                            <i class="fas fa-arrow-right"></i>
                            Go to Cart
                        </a>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Your cart is empty</h3>
                        <p>Start adding items from our closet!</p>
                        <a href="closet.php" class="btn-action btn-primary" style="display: inline-flex;">
                            <i class="fas fa-store"></i>
                            Browse Closet
                        </a>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Order History Section -->
            <section class="section-card">
                <div class="section-header">
                    <h2>
                        <i class="fas fa-history"></i>
                        Order History
                    </h2>
                    <?php if (!empty($orderHistory)): ?>
                        <span class="badge"><?php echo count($orderHistory); ?> Orders</span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($orderHistory)): ?>
                    <?php foreach ($orderHistory as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id">
                                    <i class="fas fa-receipt"></i>
                                    Order #<?php echo htmlspecialchars($order['orderID']); ?>
                                </div>
                                <span class="status-badge <?php echo $order['active_order'] ? 'status-active' : 'status-completed'; ?>">
                                    <i class="fas fa-<?php echo $order['active_order'] ? 'clock' : 'check'; ?>"></i>
                                    <?php echo $order['active_order'] ? 'Active' : 'Completed'; ?>
                                </span>
                            </div>

                            <div class="order-meta">
                                <div class="order-meta-item">
                                    <i class="far fa-calendar"></i>
                                    <strong>Placed:</strong> 
                                    <?php echo htmlspecialchars(date('M d, Y', strtotime($order['time_placed']))); ?>
                                </div>
                                <div class="order-meta-item">
                                    <i class="fas fa-box"></i>
                                    <strong>Items:</strong> 
                                    <?php echo htmlspecialchars($order['qty']); ?>
                                </div>
                                <?php if (!empty($order['expiration'])): ?>
                                <div class="order-meta-item">
                                    <i class="far fa-clock"></i>
                                    <strong>Expires:</strong> 
                                    <?php echo htmlspecialchars(date('M d, Y', strtotime($order['expiration']))); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($order['items'])): ?>
                                <ul class="order-items-list">
                                    <?php foreach ($order['items'] as $oi): ?>
                                        <li>
                                            <i class="fas fa-tshirt"></i>
                                            <?php echo htmlspecialchars($oi['description_of_item'] ?? ('Item ' . ($oi['itemID'] ?? ''))); ?> 
                                            <span style="color: #6c757d; font-size: 0.9rem;">(SKU: <?php echo htmlspecialchars($oi['sku'] ?? 'N/A'); ?>)</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p style="color: #6c757d; font-style: italic;">No items found for this order.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>No orders yet</h3>
                        <p>Your order history will appear here once you make your first purchase.</p>
                        <a href="closet.php" class="btn-action btn-primary" style="display: inline-flex;">
                            <i class="fas fa-shopping-bag"></i>
                            Start Shopping
                        </a>
                    </div>
                <?php endif; ?>
            </section>

        </div>
    </div>
</div>

<?= $footer->render() ?>

<script src="js/hamburger.js" defer></script>
</body>
</html>