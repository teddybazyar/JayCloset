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

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new ShoppingCart();
}

$cart = $_SESSION['cart'];
$items = $cart->getItems();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($title); ?></title>
	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    margin: 0;
    padding: 0;
}

.cart-container {
    width: 90%;
    max-width: 1200px;
    margin: 80px auto 60px;
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.cart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 3px solid #B4D1F8;
}

.cart-header h1 {
    color: #0a2240;
    font-size: 2.5rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.cart-header h1 i {
    color: #004b98;
}

.cart-badge {
    background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(225, 38, 28, 0.3);
}

/* Empty Cart Styling */
.empty-cart-container {
    text-align: center;
    padding: 80px 20px;
}

.empty-cart-icon {
    font-size: 120px;
    color: #c8c8c8;
    margin-bottom: 20px;
}

.empty-cart h2 {
    color: #0a2240;
    font-size: 2rem;
    margin-bottom: 15px;
}

.empty-cart p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

/* Cart Items Grid */
.cart-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 40px;
}

.cart-item {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 25px;
    display: grid;
    grid-template-columns: 100px 2fr 1fr auto;
    gap: 25px;
    align-items: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.cart-item::before {
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

.cart-item:hover {
    border-color: #B4D1F8;
    box-shadow: 0 8px 25px rgba(0, 75, 152, 0.15);
    transform: translateY(-3px);
}

.cart-item:hover::before {
    transform: scaleY(1);
}

.item-image-wrapper {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.item-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.item-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #0a2240;
    margin: 0;
}

.item-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 5px;
}

.spec-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    color: #495057;
    border: 1px solid #dee2e6;
}

.spec-badge i {
    color: #004b98;
    font-size: 0.9rem;
}

.item-meta {
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: flex-end;
}

.item-sku {
    background: linear-gradient(135deg, #0a2240 0%, #004b98 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    font-family: 'Courier New', monospace;
}

.reserved-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.remove-form {
    display: inline;
}

.remove-btn {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.remove-btn:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

.remove-btn:active {
    transform: translateY(0);
}

/* Cart Actions */
.cart-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 30px;
    border-top: 3px solid #e9ecef;
    margin-top: 20px;
}

.btn-secondary, .btn-primary {
    padding: 16px 40px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-secondary {
    background: white;
    color: #004b98;
    border: 3px solid #004b98;
}

.btn-secondary:hover {
    background: #004b98;
    color: white;
    transform: translateX(-5px);
}

.btn-primary {
    background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
    color: white;
    box-shadow: 0 6px 20px rgba(225, 38, 28, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #c82333 0%, #e1261c 100%);
    transform: translateX(5px);
    box-shadow: 0 8px 25px rgba(225, 38, 28, 0.4);
}

/* Cart Summary */
.cart-summary {
    background: linear-gradient(135deg, #B4D1F8 0%, #3db5e6 100%);
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 25px rgba(0, 75, 152, 0.2);
}

.cart-summary h2 {
    color: #0a2240;
    margin: 0 0 20px 0;
    font-size: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    color: #0a2240;
    font-size: 1.1rem;
    font-weight: 500;
}

.summary-row.total {
    border-top: 3px solid white;
    margin-top: 15px;
    padding-top: 20px;
    font-size: 1.4rem;
    font-weight: 700;
}

/* Responsive Design */
@media (max-width: 768px) {
    .cart-container {
        width: 95%;
        padding: 20px;
        margin: 60px auto 40px;
    }

    .cart-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .cart-header h1 {
        font-size: 1.8rem;
    }

    .cart-item {
        grid-template-columns: 1fr;
        gap: 15px;
        text-align: center;
    }

    .item-image-wrapper {
        margin: 0 auto;
    }

    .item-specs {
        justify-content: center;
    }

    .item-meta {
        align-items: center;
    }

    .cart-actions {
        flex-direction: column;
        gap: 15px;
    }

    .btn-secondary, .btn-primary {
        width: 100%;
        justify-content: center;
    }
}

/* Loading Animation */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.loading {
    animation: pulse 1.5s ease-in-out infinite;
}
</style>

<body>
    <?php $nav->display() ?>

    <main id="cart" class="cart-container">
        <div class="cart-header">
            <h1>
                <i class="fas fa-shopping-cart"></i>
                Your Shopping Cart
            </h1>
            <?php if (!empty($items)): ?>
                <span class="cart-badge">
                    <?= count($items) ?> <?= count($items) === 1 ? 'Item' : 'Items' ?>
                </span>
            <?php endif; ?>
        </div>

        <?php if (empty($items)): ?>
            <div class="empty-cart-container">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="empty-cart">
                    <h2>Your cart is empty</h2>
                    <p>Browse our closet and add items you'd like to reserve!</p>
                    <a href="closet.php" class="btn-primary">
                        <i class="fas fa-arrow-left"></i>
                        Start Shopping
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-summary">
                <h2><i class="fas fa-list-check"></i> Order Summary</h2>
                <div class="summary-row">
                    <span>Total Items:</span>
                    <span><?= count($items) ?></span>
                </div>
                <div class="summary-row total">
                    <span>Ready for Checkout</span>
                    <span><i class="fas fa-check-circle"></i></span>
                </div>
            </div>

            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item">
                        <div class="item-image-wrapper">
                            <?php
                                $clothesImgDir = '../images/items/';
                                $clothesImgPath = $clothesImgDir . $item->getItemID() . "/" . $item->getItemID() . "-1.png";
                                if (!file_exists($clothesImgPath)) {
                                    $clothesImgPath = $clothesImgDir . "missingImages.png";
                                }
                            ?>
                            <img src="<?= htmlspecialchars($clothesImgPath) ?>" alt="<?= htmlspecialchars($item->getTitle()) ?>">
                        </div>

                        <div class="item-details">
                            <h3 class="item-title"><?= htmlspecialchars($item->getTitle()) ?></h3>
                            <div class="item-specs">
                                <span class="spec-badge">
                                    <i class="fas fa-palette"></i>
                                    <?= htmlspecialchars($item->getColor()) ?>
                                </span>
                                <span class="spec-badge">
                                    <i class="fas fa-ruler"></i>
                                    Size <?= htmlspecialchars($item->getSize()) ?>
                                </span>
                                <span class="spec-badge">
                                    <i class="fas fa-venus-mars"></i>
                                    <?= htmlspecialchars($item->getGender()) ?>
                                </span>
                            </div>
                        </div>

                        <div class="item-meta">
                            <span class="item-sku">
                                SKU: <?= htmlspecialchars($item->getSKU()) ?>
                            </span>
                            <?php if ($item->isReserved()): ?>
                                <span class="reserved-badge">
                                    <i class="fas fa-lock"></i>
                                    Reserved
                                </span>
                            <?php endif; ?>
                        </div>

                        <form method="POST" action="processes/removefromcart.php" class="remove-form">
                            <input type="hidden" name="itemID" value="<?= $item->getItemID() ?>">
                            <button type="submit" class="remove-btn">
                                <i class="fas fa-trash-alt"></i>
                                Remove
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-actions">
                <a href="closet.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Continue Shopping
                </a>
                <a href="processes/checkout.php" class="btn-primary">
                    Proceed to Checkout
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </main>

    <?= $footer->render() ?>

    <script src="js/hamburger.js" defer></script>
</body>
</html>