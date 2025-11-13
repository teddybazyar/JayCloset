<?php
session_start();

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';
require_once __DIR__ . '/includes/db_cred.php';
require_once __DIR__ . '/includes/jayclosetdb.php';

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");
$title = 'Closet - Jay Closet';

$getItemsSQL = "SELECT * FROM descript;";
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

<div class="sidenav">
    <a href="#">Women</a>
    <a href="#">Men</a>
    <a href="#">Unisex</a>
    <a href="#">Tops</a>
    <a href="#">Bottoms</a>
    <a href="#">Jacket</a>
    <a href="#">Shoes</a>
    <a href="#">Dress</a>
    <a href="#">Others</a>
</div>

<div class="main-content"> <!-- EVERYTHING inside MAIN will move right -->

    <h1 style="text-align:center; padding:40px 0;">Closet</h1>

    <?php if (!empty($allItems)): ?>
        <div class="item-container">
            <?php foreach ($allItems as $row): ?>
                <div class="itembox">
                    <img src="../images/items/missingImages.png" class="item-image">
                    <h2><?= htmlspecialchars($row["description_of_item"]) ?></h2>
                    <p>Color: <?= htmlspecialchars($row["color"]) ?></p>
                    <p>Size: <?= htmlspecialchars($row["size"]) ?></p>
                    <p>Gender: <?= htmlspecialchars($row["gender"]) ?></p>
                    <p>Availability: <?= $row["reserved"] ? "Reserved" : "Available" ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No items found.</p>
    <?php endif; ?>

</div> <!-- END MAIN -->

<?= $footer->render(); ?>


</body>
</html>
