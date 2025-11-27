<?php
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

session_start();

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/MainPageContent.php';
require_once __DIR__ . '/includes/Footer.php';

// Check if user is admin and set navbar accordingly
$isAdmin = isset($_SESSION["ADMIN"]) && $_SESSION["ADMIN"] == 1;

if ($isAdmin) {
    $navLinks = [
        'Admin' => ['url' => 'admin/admin.php', 'icon' => 'fas fa-home'],
        'Closet' => ['url' => 'closet.php', 'icon' => 'fa fa-list'],
        'Users' => ['url' => 'admin/users.php', 'icon' => 'fas fa-users'],
        'Logout' => ['url' => 'login/logout.php', 'icon' => 'fas fa-sign-out-alt']
    ];
    $nav = new Navbar($navLinks);
} else {
    $nav = new Navbar(); // Use default links
}

$footer = new Footer("Jay Closet 2025");
$title = 'Welcome to Jay Closet!';

$page = isset($_GET['page']) ? $_GET['page'] : 'welcome';

// Handle page content
switch ($page) {
    case 'welcome':
        $content = MainPageContent::render();
        break;
    case 'login':
        ob_start();
        require_once __DIR__ . '/login/loginform.php';
        $content = ob_get_clean();
        break;
    case 'create':
        ob_start();
        require_once __DIR__ . '/login/createform.php';
        $content = ob_get_clean();
        break;
    default:
        $content = MainPageContent::render();
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Enhanced Index Page Styling */
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #0a2240 0%, #004b98 50%, #3db5e6 100%);
            padding: 80px 20px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .hero-section::before {
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

        .hero-section .h1 {
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }

        .hero-section h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mission Statement Box */
        .mission-box {
            background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(225, 38, 28, 0.4);
            max-width: 900px;
            margin: -40px auto 60px;
            position: relative;
            z-index: 10;
            animation: fadeInUp 1s ease 0.3s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .mission-box h3 {
            color: white;
            font-size: clamp(1rem, 3vw, 1.4rem);
            line-height: 1.8;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        /* Enhanced Slideshow */
        .slideshow-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .slideshow-container {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            background: white;
        }

        .mySlides {
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { opacity: 0.8; }
            to { opacity: 1; }
        }

        .mySlides img {
            border-radius: 20px;
        }

        .prev, .next {
            background: rgba(0, 75, 152, 0.8);
            color: white;
            padding: 20px;
            border-radius: 0;
            font-size: 24px;
        }

        .prev:hover, .next:hover {
            background: rgba(225, 38, 28, 0.9);
        }

        .dot {
            width: 18px;
            height: 18px;
            background-color: #c8c8c8;
            transition: all 0.3s ease;
        }

        .dot:hover, .dot.active {
            background-color: #004b98;
            transform: scale(1.2);
        }

        /* Info Card Enhanced */
        .info-card {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 80px auto;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(180deg, #004b98 0%, #3db5e6 100%);
        }

        .info-card strong {
            color: #0a2240;
            font-size: 1.3rem;
        }

        .info-card p {
            color: #495057;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* Category Cards */
        .category-section {
            max-width: 1400px;
            margin: 80px auto;
            padding: 0 20px;
        }

        .category-title {
            text-align: center;
            font-size: 2.5rem;
            color: #0a2240;
            margin-bottom: 50px;
            font-weight: 700;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 0;
        }

        .column {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: auto;
            min-height: 400px;
            position: relative;
        }

        .column::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #004b98 0%, #3db5e6 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .column:hover::before {
            transform: scaleX(1);
        }

        .column:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: 0 20px 60px rgba(0, 75, 152, 0.3);
        }

        .column a {
            display: block;
            padding: 40px 30px;
            text-align: center;
        }

        .column h1 {
            color: #0a2240;
            font-size: 2rem;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .column .main-item-image {
            width: 100%;
            max-width: 280px;
            height: 280px;
            object-fit: contain;
            border-radius: 15px;
            transition: transform 0.4s ease;
            background: #f8f9fa;
            padding: 20px;
        }

        .column:hover .main-item-image {
            transform: scale(1.1) rotate(3deg);
        }

        /* CCCE Section */
        .ccce-section {
            background: linear-gradient(135deg, #B4D1F8 0%, #3db5e6 100%);
            padding: 60px;
            border-radius: 30px;
            margin: 80px auto;
            max-width: 1200px;
            box-shadow: 0 20px 60px rgba(0, 75, 152, 0.3);
            position: relative;
            overflow: hidden;
        }

        .ccce-section::after {
            content: '🎓';
            position: absolute;
            right: 30px;
            bottom: 30px;
            font-size: 120px;
            opacity: 0.1;
        }

        .ccce-section strong {
            color: #0a2240;
            font-size: 1.5rem;
            display: block;
            margin-bottom: 15px;
        }

        .ccce-section p {
            color: #0a2240;
            font-size: 1.1rem;
            line-height: 1.9;
        }

        .ccce-section a {
            color: #e1261c;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 2px solid #e1261c;
            transition: all 0.3s ease;
        }

        .ccce-section a:hover {
            color: #0a2240;
            border-bottom-color: #0a2240;
        }

        /* Team Preview */
        .team-preview {
            background: white;
            border-radius: 30px;
            padding: 60px;
            margin: 80px auto;
            max-width: 1000px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }

        .team-preview::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 5px;
            background: linear-gradient(90deg, #e1261c 0%, #f0928d 100%);
            border-radius: 0 0 10px 10px;
        }

        .team-preview h2 {
            color: #0a2240;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .employee-photo {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 6px solid #B4D1F8;
            box-shadow: 0 10px 40px rgba(0, 75, 152, 0.3);
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .employee-photo:hover {
            transform: scale(1.1);
            border-color: #e1261c;
            box-shadow: 0 15px 50px rgba(225, 38, 28, 0.4);
        }

        /* CTA Button */
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #e1261c 0%, #f0928d 100%);
            color: white;
            padding: 18px 45px;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(225, 38, 28, 0.4);
            transition: all 0.3s ease;
            margin: 20px 0;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(225, 38, 28, 0.5);
            background: linear-gradient(135deg, #c82333 0%, #e1261c 100%);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-section {
                padding: 50px 15px;
            }

            .mission-box {
                padding: 25px;
                margin: -30px 15px 40px;
            }

            .info-card, .ccce-section, .team-preview {
                padding: 30px 20px;
                margin: 40px 15px;
            }

            .category-title {
                font-size: 2rem;
            }

            .row {
                grid-template-columns: 1fr;
            }
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Loading Animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
    </style>
</head>
<body>

<?php 
    $nav->display();
    echo $content;
    echo $footer->render();
?>

<script src="js/hamburger.js" defer></script>
</body>
</html>