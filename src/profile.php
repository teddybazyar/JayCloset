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
</head>
<body>
<?php $nav->display(); ?>
<main class="sheet create-page">
    <div class="container">
        <h1>Your Profile</h1>

        <section>
            <h2>User Info</h2>
            <p><strong>Student ID:</strong> <?php echo $userID; ?></p>
            <p><strong>Email:</strong> <?php echo $userEmail; ?></p>
            <p><a href="update_user.php">Edit your information</a></p>
        </section>

        <section>
            <h2>Cart</h2>
            <?php if (!empty($cartItems)): ?>
                <ul>
                    <?php foreach ($cartItems as $item): ?>
                        <li>
                            <?php
                            // The cart may store Product objects or arrays
                            if (is_object($item)) {
                                // Prefer public getter methods when available
                                if (method_exists($item, 'getTitle')) {
                                    echo htmlspecialchars($item->getTitle());
                                } elseif (method_exists($item, 'getDescription')) {
                                    echo htmlspecialchars($item->getDescription());
                                } elseif (property_exists($item, 'description')) {
                                    // Fallback - unlikely for private properties but kept for resilience
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
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p><a href="cart_page.php">Go to Cart Page</a></p>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </section>

        <section>
            <h2>Order History</h2>
            <?php if (!empty($orderHistory)): ?>
                <?php foreach ($orderHistory as $order): ?>
                    <div class="order">
                        <h3>Order #<?php echo htmlspecialchars($order['orderID']); ?></h3>
                        <p>Placed: <?php echo htmlspecialchars($order['time_placed']); ?> | Qty: <?php echo htmlspecialchars($order['qty']); ?> | Status: <?php echo htmlspecialchars($order['active_order'] ? 'Active' : 'Completed'); ?></p>
                        <?php if (!empty($order['items'])): ?>
                            <ul>
                                <?php foreach ($order['items'] as $oi): ?>
                                    <li><?php echo htmlspecialchars($oi['description_of_item'] ?? ('Item ' . ($oi['itemID'] ?? ''))); ?> (SKU: <?php echo htmlspecialchars($oi['sku'] ?? ''); ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No items found for this order.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </section>

        <section>
            <h2>Actions</h2>
            <p><a href="login/logout.php">Log out</a></p>
        </section>
    </div>
</main>
    <?= $footer->render() ?>

    <script src="js/hamburger.js" defer></script>
</body>
</html>
