<?php
require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';
session_start();

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");

$orderID = $_GET['orderID'] ?? null;
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Order Confirmation</title></head>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<body>
    <?php $nav->display(); ?>

    <main style="max-width:800px;margin:40px auto;padding:20px;background:#fff;border-radius:8px;">
        <?php if ($orderID): ?>
            <h1>Thank you!</h1>
            <p>Your order has been placed. Order ID: <strong><?= htmlspecialchars($orderID) ?></strong></p>
            <p>A confirmation email was sent to the email associated with your account.</p>
            <p><a href="closet.php">Continue shopping</a></p>
        <?php else: ?>
            <h1>Order not found</h1>
            <p>There was an issue with your order. Please contact support.</p>
        <?php endif; ?>
    </main>

    <?= $footer->render() ?>
</body>
</html>