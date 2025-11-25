<?php
require_once __DIR__ . '/../includes/jayclosetdb.php';
require_once __DIR__ . '/cart.php';
require_once __DIR__ . '/products.php';
require_once __DIR__ . '/send_user_email.php';
require_once __DIR__ . '/send_admin_email.php';
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart']->getItems())) {
    header("Location: ../cart_page.php");
    exit();
}

$userId = null;
$user = null;
// Check for UserID from your login system
if (isset($_SESSION["UserID"])) {
    $userId = (int)$_SESSION["UserID"];
    
    $userData = jayclosetdb::getDataFromSQL(
        "SELECT ID, fname, lname, email FROM users WHERE ID = ?", 
        [$userId]
    );
    
    if (!empty($userData)) {
        $user = $userData[0];
    }
}

if ($userId === null || $user === null) {
    echo "You must be logged in to checkout. Please <a href='../index.php?page=login'>login</a>.";
    exit();
}
if ($userId === null || $user === null) {
    echo "You must be logged in to checkout. Please <a href='index.php?page=login'>login</a>.";
    exit();
}

$cart = $_SESSION['cart'];
$items = $cart->getItems();

try {
    jayclosetdb::startTransaction();

    $qty = count($items);
    
    $sqlInsertOrder = "INSERT INTO orders (ID, qty, time_placed, expiration, active_order) 
                       VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 1)";
    jayclosetdb::executeSQL($sqlInsertOrder, [$userId, $qty], true);
    
    $result = jayclosetdb::getDataFromSQL("SELECT LAST_INSERT_ID() as id");
    $orderID = (int)$result[0]['id'];

    $sqlInsertItem = "INSERT INTO items (itemID, orderID, sku) VALUES (?, ?, ?)";
    $sqlUpdateReserved = "UPDATE descript SET reserved = 1 WHERE itemID = ?";

    foreach ($items as $prod) {
        $itemID = $prod->getItemID();
        $sku = $prod->getSKU();

        jayclosetdb::executeSQL($sqlInsertItem, [$itemID, $orderID, $sku]);
        jayclosetdb::executeSQL($sqlUpdateReserved, [$itemID]);
    }

    jayclosetdb::commitTransaction();

    $cart->empty();
    $_SESSION['cart'] = $cart;

    $orderRow = jayclosetdb::getDataFromSQL("SELECT * FROM orders WHERE orderID = ?", [$orderID]);
    $orderInfo = !empty($orderRow) ? $orderRow[0] : null;

    $itemDetails = [];
    foreach ($items as $p) {
        $itemDetails[] = [
            'itemID' => $p->getItemID(),
            'sku' => $p->getSKU(),
            'title' => $p->getTitle(),
            'description' => $p->getDescription()
        ];
    }

    send_user_email($user['email'], $user['fname'] ?? '', $user['lname'] ?? '', $orderID, $itemDetails, $orderInfo);
    send_admin_email($orderID, $user, $itemDetails, $orderInfo);

    header("Location: ../order_confirmation.php?orderID=" . urlencode($orderID));
    exit();

} catch (Exception $e) {
    jayclosetdb::rollbackTransaction();
    error_log("Checkout failed: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    exit();
}
?>