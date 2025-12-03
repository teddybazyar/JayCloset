<?php
require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';
session_start();

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");

$orderID = $_GET['orderID'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $orderID ? 'Order Confirmed!' : 'Order Issue' ?> - Jay Closet</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Enhanced Order Confirmation Page Styling */
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        /* Main Container */
        .confirmation-container {
            max-width: 900px;
            margin: 80px auto 80px;
            padding: 0 20px;
            position: relative;
        }

        /* Success Card */
        .confirmation-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: slideUp 0.6s ease;
            position: relative;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Success Header */
        .success-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 60px 40px 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .success-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: float 15s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-20px, 20px) rotate(180deg); }
        }

        .success-icon-wrapper {
            width: 140px;
            height: 140px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: checkPop 0.6s ease 0.3s backwards;
            position: relative;
            z-index: 2;
        }

        @keyframes checkPop {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon {
            font-size: 5rem;
            color: #28a745;
            animation: iconSpin 0.6s ease 0.5s backwards;
        }

        @keyframes iconSpin {
            from {
                transform: rotate(-180deg);
                opacity: 0;
            }
            to {
                transform: rotate(0);
                opacity: 1;
            }
        }

        .success-header h1 {
            color: white;
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 800;
            margin: 0 0 15px 0;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
            animation: fadeInDown 0.6s ease 0.4s backwards;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-header p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.3rem;
            margin: 0;
            position: relative;
            z-index: 2;
            animation: fadeInUp 0.6s ease 0.5s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card Body */
        .confirmation-body {
            padding: 50px 40px;
            position: relative;
        }

        .order-id-section {
            background: linear-gradient(135deg, #B4D1F8 0%, #3db5e6 100%);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 35px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 75, 152, 0.2);
        }

        .order-id-label {
            color: #0a2240;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .order-id-value {
            color: #0a2240;
            font-size: 2.5rem;
            font-weight: 800;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Info Boxes */
        .info-boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #004b98;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 75, 152, 0.15);
        }

        .info-box-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #004b98 0%, #3db5e6 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .info-box-icon i {
            color: white;
            font-size: 1.5rem;
        }

        .info-box h3 {
            color: #0a2240;
            font-size: 1.1rem;
            margin: 0 0 8px 0;
            font-weight: 700;
        }

        .info-box p {
            color: #495057;
            margin: 0;
            line-height: 1.6;
        }

        /* What's Next Section */
        .next-steps {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            padding: 30px;
            border-radius: 15px;
            border-left: 5px solid #ffc107;
            margin-bottom: 35px;
        }

        .next-steps h2 {
            color: #856404;
            font-size: 1.5rem;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .next-steps h2 i {
            color: #f39c12;
        }

        .next-steps ol {
            margin: 0;
            padding-left: 25px;
            color: #856404;
        }

        .next-steps li {
            margin: 12px 0;
            line-height: 1.6;
            font-weight: 500;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 18px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #004b98 0%, #3db5e6 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(0, 75, 152, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 75, 152, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #004b98;
            border: 3px solid #004b98;
        }

        .btn-secondary:hover {
            background: #004b98;
            color: white;
            transform: translateY(-3px);
        }

        /* Error Card */
        .error-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: slideUp 0.6s ease;
        }

        .error-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            padding: 60px 40px 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: float 15s infinite ease-in-out;
        }

        .error-icon-wrapper {
            width: 140px;
            height: 140px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: shake 0.6s ease;
            position: relative;
            z-index: 2;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }

        .error-icon {
            font-size: 5rem;
            color: #dc3545;
        }

        .error-header h1 {
            color: white;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin: 0 0 15px 0;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }

        .error-header p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.2rem;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .error-body {
            padding: 50px 40px;
        }

        .error-details {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #dc3545;
            margin-bottom: 30px;
        }

        .error-details h3 {
            color: #721c24;
            font-size: 1.3rem;
            margin: 0 0 15px 0;
            font-weight: 700;
        }

        .error-details p {
            color: #721c24;
            margin: 8px 0;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .confirmation-container {
                margin: 50px auto;
            }

            .success-header, .error-header {
                padding: 40px 25px 60px;
            }

            .confirmation-body, .error-body {
                padding: 35px 25px;
            }

            .success-icon-wrapper, .error-icon-wrapper {
                width: 120px;
                height: 120px;
            }

            .success-icon, .error-icon {
                font-size: 4rem;
            }

            .order-id-value {
                font-size: 1.8rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .info-boxes {
                grid-template-columns: 1fr;
            }
        }

        /* Confetti Animation (Optional - for celebration effect) */
        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <?php $nav->display(); ?>

    <div class="confirmation-container">
        <?php if ($orderID): ?>
            <!-- Success State -->
            <div class="confirmation-card">
                <div class="success-header">
                    <div class="success-icon-wrapper">
                        <i class="fas fa-check-circle success-icon"></i>
                    </div>
                    <h1>Order Confirmed!</h1>
                    <p>Thank you for your reservation</p>
                </div>

                <div class="confirmation-body">
                    <!-- Order ID Display -->
                    <div class="order-id-section">
                        <div class="order-id-label">Your Order ID</div>
                        <div class="order-id-value">#<?= htmlspecialchars($orderID) ?></div>
                    </div>

                    <!-- Information Boxes -->
                    <div class="info-boxes">
                        <div class="info-box">
                            <div class="info-box-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3>Email Confirmation</h3>
                            <p>A confirmation email has been sent to your registered email address with all order details.</p>
                        </div>

                        <div class="info-box">
                            <div class="info-box-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>Pickup Information</h3>
                            <p>You can pick up your reserved items at the Blue Jay Career Closet (BSC 201) during business hours.</p>
                        </div>

                        <div class="info-box">
                            <div class="info-box-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h3>View Your Orders</h3>
                            <p>Check your order history and track your reservations in your profile page.</p>
                        </div>
                    </div>

                    <!-- What's Next -->
                    <div class="next-steps">
                        <h2>
                            <i class="fas fa-list-check"></i>
                            What's Next?
                        </h2>
                        <ol>
                            <li>Check your email for order confirmation and details</li>
                            <li>Visit the Blue Jay Career Closet to pick up your items</li>
                            <li>Return items by the expiration date shown in your order</li>
                            <li>Browse our closet for more professional attire options!</li>
                        </ol>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="closet.php" class="btn btn-primary">
                            <i class="fas fa-shopping-bag"></i>
                            Continue Shopping
                        </a>
                        <a href="profile.php" class="btn btn-secondary">
                            <i class="fas fa-user"></i>
                            View Profile
                        </a>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Error State -->
            <div class="error-card">
                <div class="error-header">
                    <div class="error-icon-wrapper">
                        <i class="fas fa-exclamation-triangle error-icon"></i>
                    </div>
                    <h1>Order Not Found</h1>
                    <p>We couldn't locate your order</p>
                </div>

                <div class="error-body">
                    <div class="error-details">
                        <h3>What happened?</h3>
                        <p>There was an issue processing or locating your order. This could be due to:</p>
                        <ul>
                            <li>The order ID is missing or invalid</li>
                            <li>A technical issue occurred during checkout</li>
                            <li>The order was not successfully completed</li>
                        </ul>
                    </div>

                    <div class="info-box">
                        <div class="info-box-icon">
                            <i class="fas fa-life-ring"></i>
                        </div>
                        <h3>Need Help?</h3>
                        <p>If you believe this is an error, please contact our support team at the Blue Jay Career Closet. We're here to help!</p>
                    </div>

                    <div class="action-buttons" style="margin-top: 30px;">
                        <a href="closet.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                            Back to Closet
                        </a>
                        <a href="cart_page.php" class="btn btn-secondary">
                            <i class="fas fa-shopping-cart"></i>
                            View Cart
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?= $footer->render() ?>

    <script src="js/hamburger.js" defer></script>
</body>
</html>