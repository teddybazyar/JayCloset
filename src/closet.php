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
$title = 'Closet - Jay Closet';
$clothesImgDir = '../images/items/';

// RESET ALL FILTERS
if (isset($_GET['reset'])) {
    unset($_SESSION['gender'], $_SESSION['category'], $_SESSION['size'], $_SESSION['color']);
    header("Location: closet.php");
    exit;
}

// SAVE GET → SESSION
if (isset($_GET['gender'])) {
    $_SESSION['gender'] = $_GET['gender'];
}

if (!isset($_GET['gender'])){
    $_SESSION['gender'] = "All";
}

if (isset($_GET['category'])) {
    $_SESSION['category'] = $_GET['category'];
}

if (!isset($_GET['category'])){
    $_SESSION['category'] = "All";
}

if (isset($_GET['size'])) {
    $_SESSION['size'] = $_GET['size'];
} 

if (!isset($_GET['size'])){
    $_SESSION['size'] = "All";
}

if (isset($_GET['color'])) {
    $_SESSION['color'] = $_GET['color'];
}

if (!isset($_GET['color'])){
    $_SESSION['color'] = "All";
}

$conditions = ["reserved = 0"];  // always include

// Gender
if (!empty($_SESSION['gender']) && $_SESSION['gender'] != 'All') {
    $conditions[] = "gender = '" . addslashes($_SESSION['gender']) . "'";
}

// Categories
if (!empty($_SESSION['category']) && $_SESSION['category'] != 'All') {
    $conditions[] = "categories = '" . addslashes($_SESSION['category']) . "'";
}

// Size
if (!empty($_SESSION['size']) && $_SESSION['size'] != 'All') {
    $conditions[] = "size = '" . addslashes($_SESSION['size']) . "'";
}

// Color
if (!empty($_SESSION['color']) && $_SESSION['color'] != 'All') {
    $conditions[] = "color = '" . addslashes($_SESSION['color']) . "'";
}


// FINAL QUERY
$whereSQL = "WHERE " . implode(" AND ", $conditions);
$getItemsSQL = "SELECT * FROM descript $whereSQL;";
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
    <form method="GET">

        <h4>Search by Gender</h4>
        <?php
            $genders = ["All", "Men", "Women", "Unisex"];
            foreach ($genders as $g):
        ?>
        <label>
            <input type="radio" name="gender" value="<?= $g ?>"
                <?= ($g === ($_SESSION['gender'] ?? null)) ? "checked" : "" ?>>
            <?= $g ?>
        </label><br>
        <?php endforeach; ?>
        <br>

        <h4>Search by Category</h4>
        <?php
            $categories = [
                "All","Tops/Blouse","Sweater/Vest/Cardigan",
                "Jacket","Bottoms","Shoes","Dress","Others"
            ];
            foreach ($categories as $c):
        ?>
        <label>
            <input type="radio" name="category" value="<?= $c ?>"
                <?= ($c === ($_SESSION['category'] ?? null)) ? "checked" : "" ?>>
            <?= $c ?>
        </label><br>
        <?php endforeach; ?>
        <br>

        <h4>Search by Size</h4>
        <?php
            $sizes = ["All","XS","S","M","L","XL","XXL","5","6","7","8","9","10","11","12"];
            foreach ($sizes as $s):
        ?>
        <label>
            <input type="radio" name="size" value="<?= $s ?>"
                <?= ($s === ($_SESSION['size'] ?? null)) ? "checked" : "" ?>>
            <?= $s ?>
        </label><br>
        <?php endforeach; ?>
        <br>

        <h4>Search by Color</h4>
        <?php
            $colors = ["All","Red","Yellow","Pink","Blue","Purple","Orange","Green","Beige","Brown","Black","White","Grey","Multi-color"];
            foreach ($colors as $clr):
        ?>
        <label>
            <input type="radio" name="color" value="<?= $clr ?>"
                <?= ($clr === ($_SESSION['color'] ?? null)) ? "checked" : "" ?>>
            <?= $clr ?>
        </label><br>
        <?php endforeach; ?>
        <br>

        <button type="submit" class="apply-btn">Apply Filters</button>
        <button type="submit" name="reset" value="1" class="reset-btn">Reset</button>

    </form>
</div>



<div class="main-content"> <!-- EVERYTHING inside MAIN will move right -->
    <!-- display filter -->
    <!-- I think I can shorten this lines -->
    <h1>
        <?php echo "Result"; ?>
    <h1>
    <h2>
        <?php
        // Show All
        if ($_SESSION['gender'] == 'All' &&
            $_SESSION['category'] == 'All' &&
            $_SESSION['size'] == 'All' &&
            $_SESSION['color'] == 'All') {

                echo "All Items";
        
        } else {
            echo "Gender: " . htmlspecialchars($_SESSION['gender']) . ', ';
            echo "Category: " . htmlspecialchars($_SESSION['category']) . ', ';
            echo "Size: " . htmlspecialchars($_SESSION['size']) . ', ';
            echo "Color: " . htmlspecialchars($_SESSION['color']);
        }
        ?>
        </h2>


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
                    <h3>
                        <a href="item.php?id=<?= urlencode($row['itemID']) ?>">
                            <?= htmlspecialchars($row['description_of_item']) ?>
                        </a>
                    </h3>

                    <p><strong>Color: </strong><?= htmlspecialchars($row["color"]) ?></p>
                    <p><strong>Size: </strong><?= htmlspecialchars($row["size"]) ?></p>
                    <p><strong>Gender: </strong><?= htmlspecialchars($row["gender"]) ?></p>
                    <p><strong>Category: </strong><?= htmlspecialchars($row["categories"]) ?></p>
                    <div class="button-wrapper">
                        <form action="processes/addtocart.php" method="POST">
                            <button type="submit">Add to Cart</button>
                        </form>
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
