<?php
session_start();

require_once __DIR__ . '/cart.php';
require_once __DIR__ . '/product.php';

if (!isset($_SESSION['cart'])) {
    header("Location: ../cart_page.php");
    exit();
}

$cart = $_SESSION['cart'];

// check if an itemID was passed
if (isset($_POST['itemID'])) {
    $itemID = (int)$_POST['itemID'];

    $found = false;
    foreach ($cart->getItems() as $item) {
        if ($item->getItemID() === $itemID) {
            $cart->removeItem($item);
            $found = true;
            break;
        }
    }

    $_SESSION['cart'] = $cart;
}

header("Location: ../cart_page.php");
exit();
?>
