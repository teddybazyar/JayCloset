<?php
require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';
require_once __DIR__ . '/includes/db_cred.php';
require_once __DIR__ . '/includes/jayclosetdb.php';
require_once __DIR__ . '/processes/cart.php';
require_once __DIR__ . '/processes/products.php';

session_start();

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");
$title = 'Cart - Jay Closet';

if (!isset($_SESSION['cart'])) { // making sure the cart exists in the session
    $_SESSION['cart'] = new ShoppingCart();
}

$cart = $_SESSION['cart'];
$items = $cart->getItems();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<title><?php echo htmlspecialchars($title); ?></title>
	<link rel="stylesheet" href="css/style.css">
</head>

<style>

body {
    font-family: Arial, sans-serif;
    background-color: #f4f8ff;
    margin: 0;
    padding: 0;
}

.cart-container {
    width: 80%;
    margin: 60px auto;
    background: white;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.cart-container h1 {
    color: #0a74da;
    text-align: center;
    margin-bottom: 20px;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th {
    background-color: #0a74da;
    color: white;
    padding: 12px;
    text-align: left;
}

.cart-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.remove-form {
    display: inline;
}

.remove-btn {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.remove-btn:hover {
    background-color: #b82c3a;
}

.empty-cart {
    text-align: center;
    color: #666;
    font-size: 18px;
    margin-top: 40px;
}
</style>
<body>
    <?php $nav->display() ?>

    <main id="cart" class="cart-container">
        <h1>Your Shopping Cart</h1>

        <?php if (empty($items)): ?>
            <p class="empty-cart">Your cart is empty.</p>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Title</th>
                        <th>Color</th>
                        <th>SKU</th>
                        <th>Gender</th>
                        <th>Description</th>
                        <th>Size</th>
                        <th>Reserved</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item->getItemID()) ?></td>
                            <td><?= htmlspecialchars($item->getTitle()) ?></td>
                            <td><?= htmlspecialchars($item->getColor()) ?></td>
                            <td><?= htmlspecialchars($item->getSKU()) ?></td>
                            <td><?= htmlspecialchars($item->getGender()) ?></td>
                            <td><?= htmlspecialchars($item->getDescription()) ?></td>
                            <td><?= htmlspecialchars($item->getSize()) ?></td>
                            <td><?= $item->isReserved() ? 'Yes' : 'No' ?></td>
                            <td>
                                <form method="POST" action="processes/removefromcart.php" class="remove-form">
                                    <input type="hidden" name="itemID" value="<?= $item->getItemID() ?>">
                                    <button type="submit" class="remove-btn">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; margin-top: 25px;">
                <a href="closet.php" class="btn-secondary">Continue Shopping</a>
                <a href="processes/checkout.php" class="btn-primary">Checkout</a>
            </div>
        <?php endif; ?>
    </main>

    <?= $footer->render() ?>
</body>
</html>