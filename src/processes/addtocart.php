<?php
require_once __DIR__ . '/../includes/jayclosetdb.php';
require_once __DIR__ . '/cart.php';
require_once __DIR__ . '/products.php';
session_start();

$userId = null;
$user = null;
if ($userId === null || $user === null) {
    echo "You must be logged in to checkout. Please <a href='../index.php?page=login'>login</a>.";
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new ShoppingCart();
}

$cart = $_SESSION['cart'];

if (isset($_POST['itemID'])) {
    $itemID = (int)$_POST['itemID'];

    $sql = "SELECT * FROM descript WHERE itemID = ?";
    $result = jayclosetdb::getDataFromSQL($sql, [$itemID]);

    if (!empty($result)) {
        $row = $result[0];
        $product = new Product(
            $row['itemID'],
            $row['description_of_item'],
            $row['color'],
            $row['sku'],
            $row['gender'],
            $row['description_of_item'],
            $row['size'],
            (bool)$row['reserved']
        );
        $cart->addItem($product);
        $_SESSION['cart'] = $cart;
    }
}

header("Location: ../cart_page.php");
exit();
?>