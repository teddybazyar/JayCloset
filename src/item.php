<?php
    session_start();
    require_once __DIR__ . '/includes/Navbar.php';
    require_once __DIR__ . '/includes/Footer.php';
    require_once __DIR__ . '/includes/db_cred.php';
    require_once __DIR__ . '/includes/jayclosetdb.php';

    // Check if user is admin
    $isAdmin = isset($_SESSION["ADMIN"]) && $_SESSION["ADMIN"] == 1;

    // Set navbar links based on admin status
    if ($isAdmin) {
        $navLinks = [
            'Home' => ['url' => 'index.php', 'icon' => 'fas fa-home'],
            'Closet' => ['url' => 'closet.php', 'icon' => 'fa fa-list'],
            'Users' => ['url' => 'admin/users.php', 'icon' => 'fas fa-users'],
            'Logout' => ['url' => 'login/logout.php', 'icon' => 'fas fa-sign-out-alt']
        ];
        $nav = new Navbar($navLinks);
    } else {
        $nav = new Navbar(); // Use default links
    }

    $footer = new Footer("Jay Closet 2025");
    $title = 'Items - Jay Closet';

    $itemID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$itemID) {
        die("Invalid item ID.");
    }
    $itemName     = null;
    $itemGender   = null;
    $itemCategory = null;
    $itemSize     = null;
    $itemColor    = null;

    $getItem = jayclosetdb::getDataFromSQL("SELECT description_of_item, gender, categories, size, color FROM descript WHERE itemID = $itemID;");

    if (!empty($getItem)) {
        $item = $getItem[0];

        $itemName     = $item['description_of_item'];
        $itemGender   = $item['gender'];
        $itemCategory = $item['categories'];
        $itemSize     = $item['size'];
        $itemColor    = $item['color'];
    } else {
        die("Item not found.");
    }

// Images

$imageDir = "../images/items/$itemID";
$imagePathArray = [];

if (is_dir($imageDir)) {

    $patterns = ["*.png", "*.jpg", "*.jpeg", "*.gif", "*.webp"];

    foreach ($patterns as $pattern) {
        foreach (glob($imageDir . '/' . $pattern) as $file) {
            $imagePathArray[] = $file;
        }
    }

    if (empty($imagePathArray)) {
        $imagePathArray[] = "../images/items/missingImages.png";
    }

} else {
    $imagePathArray[] = "../images/items/missingImages.png";
}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars($title) ?></title>
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <?php $nav->display();?>
        <!-- Item Name -->
        <div class='h1'>
            <h1><?= htmlspecialchars($itemName)?></h1>
        </div>

        <div class='itemPage_container'>
            <div class='item_img_container'>
            <?php foreach ($imagePathArray as $img): ?>
                <img src="<?= htmlspecialchars($img) ?>" class="itemImgs">
            <?php endforeach; ?>
            </div>

            <div class='item_dscr_container'>
                <p><strong>Color: </strong><?=htmlspecialchars($itemColor)?></p>
                <p><strong>Size: </strong><?=htmlspecialchars($itemSize)?></p>
                <p><strong>Gender: </strong> <?=htmlspecialchars($itemGender)?></p>
                <p><strong>Category: </strong><?=htmlspecialchars($itemCategory)?></p>
                <div class="button-wrapper">
                    <form action="processes/addtocart.php" method="POST">
                        <input type="hidden" name="itemID" value="<?= $itemID ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            </div>
            
        </div>
        <?= $footer->render(); ?>
    </body>
</html>