<?php
session_start();

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';
require_once __DIR__ . '/includes/db_cred.php';
require_once __DIR__ . '/includes/jayclosetdb.php';

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");
$title = 'Closet - Jay Closet';
$clothesImgDir = '../images/items/';

// Filter //
$filterGender = $_GET['gender'] ?? null;
$filterCategory = $_GET['categories'] ?? null;

# SQL - gender, category, and all
if ($filterGender) {
    $getItemsSQL = "SELECT * FROM descript WHERE reserved = 0 AND gender = '" . $filterGender . "'";
}
else if ($filterCategory) {
    $getItemsSQL = "SELECT * FROM descript WHERE reserved = 0 AND categories = '" . $filterCategory . "'";
}
else {
    $getItemsSQL = "SELECT * FROM descript WHERE reserved = 0";
}

$allItems = jayclosetdb::getDataFromSQL($getItemsSQL);
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

<?php $nav->display(); ?>

    <div class='filterbar-container'>
        <div class='btn-filterbar'>
            <li class="active-filter"><a href="closet.php">All Items</a></li>
            <li class="active-filter"><a href="closet.php?gender=Women">Women</a></li>
            <li class="active-filter"><a href="closet.php?gender=Men">Men</a></li>
            <li class="active-filter"><a href="closet.php?gender=Unisex">Unisex</a></li>
            <li class="active-filter"><a href="closet.php?categories=Tops/Blouse">Tops/Blouse</a></li>
            <li class="active-filter"><a href="closet.php?categories=Sweater/Vest/Cardigan">Sweater/Vest/Cardigan</a></li>
            <li class="active-filter"><a href="closet.php?categories=Jacket">Jacket</a></li>
            <li class="active-filter"><a href="closet.php?categories=Bottoms">Bottoms</a></li>
            <li class="active-filter"><a href="closet.php?categories=Shoes">Shoes</a></li>
            <li class="active-filter"><a href="closet.php?categories=Dress">Dress</a></li>
            <li class="active-filter"><a href="closet.php?categories=Others">Others</a></li>
        </div>
    </div>


<!-- <div class="sidenav">
    <h4><a href="closet.php">All Items</a></h4>
    <h4>Search by Gender</h4>
    <a href="closet.php?gender=Women">Women</a>
    <a href="closet.php?gender=Men">Men</a>
    <a href="closet.php?gender=Unisex">Unisex</a>
    <h4>Search by Category</h4>
    <a href="closet.php?categories=Tops/Blouse">Tops/Blouse</a>
    <a href="closet.php?categories=Sweater/Vest/Cardigan">Sweater/Vest/Cardigan</a>
    <a href="closet.php?categories=Jacket">Jacket</a>
    <a href="closet.php?categories=Bottoms">Bottoms</a>
    <a href="closet.php?categories=Shoes">Shoes</a>
    <a href="closet.php?categories=Dress">Dress</a>
    <a href="closet.php?categories=Others">Others</a>
    <h4>Search by keyword</h4>
    <input type="text" placeholder="Search..">
    <br><br><br>
</div> -->

<div class="main-content"> <!-- EVERYTHING inside MAIN will move right -->
    <!-- display filter -->
    <h1> 
        <?php
        if ($filterGender) {
            echo htmlspecialchars($filterGender);
        } elseif ($filterCategory) {
            echo htmlspecialchars($filterCategory);
        } else {
            echo "All Items";
        }
        ?>
    </h1>


    <?php if (!empty($allItems)): ?>
        <div class="item-container">
            <?php foreach ($allItems as $row): ?>
                <div class="itembox">
                    <?php
                        $clothesImgPath = $clothesImgDir . $row["itemID"] . "/" . $row['itemID'] . "-1.png";
                        if (file_exists($clothesImgPath) == 0) {
                            $clothesImgPath = $clothesImgDir . "missingImages.png";
                        }
                    ?>
                    <img src="<?= htmlspecialchars($clothesImgPath) ?>" class='item-image'>
                    <h3><?= htmlspecialchars($row["description_of_item"]) ?></h3>
                    <p><strong>Color: </strong><?= htmlspecialchars($row["color"]) ?></p>
                    <p><strong>Size: </strong><?= htmlspecialchars($row["size"]) ?></p>
                    <p><strong>Gender: </strong><?= htmlspecialchars($row["gender"]) ?></p>
                    <p><strong>Category: </strong><?= htmlspecialchars($row["categories"]) ?></p>
                    <div class="button-wrapper">
                        <button type='button'>Add to Cart</button>
                    </div>
                    
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No items found.</p>
    <?php endif; ?>

</div> <!-- END MAIN -->

<?= $footer->render(); ?>

    <script src="js/hamburger.js" defer></script>
</body>
</html>
