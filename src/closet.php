<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Add collapsable filter button for filters.

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';
require_once __DIR__ . '/includes/db_cred.php';
require_once __DIR__ . '/includes/jayclosetdb.php';

// Check if user is admin
$isAdmin = (isset($_SESSION["ADMIN"]) && $_SESSION["ADMIN"] == 1) ? true : false;

// Set navbar links based on admin status
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
    <style>
        /* Admin Controls Styling */
        .admin-controls {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            justify-content: center;
        }

        .admin-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            font-weight: 600;
        }

        .btn-edit-item {
            background: #b8d4e8;
            color: #2d3436;
        }

        .btn-edit-item:hover {
            background: #9fc5db;
        }

        .btn-delete-item {
            background: #dc3545;
            color: white;
        }

        .btn-delete-item:hover {
            background: #c82333;
        }

        .admin-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .add-item-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .add-item-btn:hover {
            background: #218838;
            transform: translate(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }

        .admin-notice {
            text-align: center;
            padding: 1rem;
            background: #d4edda;
            border-bottom: 2px solid #28a745;
        }

        .message-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            text-align: center;
            border-bottom: 2px solid #28a745;
        }

        .message-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            text-align: center;
            border-bottom: 2px solid #dc3545;
        }
    </style>
</head>
<body>

<?php $nav->display(); ?>

<?php if ($isAdmin): ?>
    <div class="admin-notice">
        <span class="admin-badge">ADMIN MODE</span>
        <span> - Edit Controls Enabled</span>
    </div>
<?php endif; ?>

<?php
// Display success message
if (isset($_SESSION["success_message"])) {
    echo '<div class="message-success">' . htmlspecialchars($_SESSION["success_message"]) . '</div>';
    unset($_SESSION["success_message"]);
}

// Display error message
if (isset($_SESSION["error_message"])) {
    echo '<div class="message-error">' . htmlspecialchars($_SESSION["error_message"]) . '</div>';
    unset($_SESSION["error_message"]);
}
?>

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
    </h1>
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

                    <?php if ($isAdmin === true): ?>
                        <!--Admin Controls-->
                        <div class="admin-controls">
                            <button type="button" class="admin-btn btn-edit-item" onclick="editItem('<?= $row['itemID'] ?>')">Edit</button>
                            <button type="button" class="admin-btn btn-delete-item" onclick="deleteItem('<?= $row['itemID'] ?>', '<?= htmlspecialchars($row['description_of_item']) ?>')">Delete</button>
                        </div>
                    <?php else: ?>
                        <!--Regular User Controls-->
                        <div class="button-wrapper">
                            <form action="processes/addtocart.php" method="POST">
                                <input type="hidden" name="itemID" value="<?= $row['itemID'] ?>">
                                <button type="submit">Add to Cart</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No items found.</p>
    <?php endif; ?>

</div> <!-- END MAIN -->

<?php if ($isAdmin): ?>
    <button class="add-item-btn" onclick="addNewItem()">+ Add New Item</button>
<?php endif; ?>

<?= $footer->render(); ?>

    <script src="js/hamburger.js" defer></script>
    <script>
        function editItem(itemId) {
            window.location.href = 'admin/edit_item.php?id=' + itemId;
        }

        function deleteItem(itemId, itemName) {
            if (confirm('Are you sure you want to delete "' + itemName + '"? This action cannot be undone.')) {
                window.location.href = 'admin/delete_item.php?id=' + itemId;
            }
        }

        function addNewItem() {
            window.location.href = 'admin/add_item.php';
        }
    </script>
</body>
</html>
