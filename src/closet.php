<?php
session_start();

require_once __DIR__ . '/includes/Navbar.php';
require_once __DIR__ . '/includes/Footer.php';
require_once __DIR__ . '/includes/db_cred.php';
require_once __DIR__ . '/includes/jayclosetdb.php';

$nav = new Navbar();
$footer = new Footer("Jay Closet 2025");
$title = 'Closet - Jay Closet';

$content = '<main id="closet"><h1>Closet</h1></main>';
$getItemsSQL = "SELECT * FROM descript;";
$allItems = jayclosetdb::getDataFromSQL($getItemsSQL); // returns an array ... that is why num_rows causes an error

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo htmlspecialchars($title); ?></title>
	<link rel="stylesheet" href="css/style.css">

</head>
<body>
	
<?php
$nav->display();
echo "<div id='closet'>";
echo "<div class='h1'>$content</div>";

	if (!empty($allItems)) {
		echo "<div class='item-container'>";

		foreach ($allItems as $row) {
			$availabilityText = ($row["reserved"] == 1 ? "Reserved" : "Available");

			echo "<div class='itembox'>
					<img src='../images/items/missingImages.png' class='item-image'>
					<h2>" . htmlspecialchars($row["description_of_item"]) . "</h2>
				  	<p>Color: " . htmlspecialchars($row["color"]) . "</p>
					<p>Size: " . htmlspecialchars($row["size"]) . "</p>
					<p>Gender: " . htmlspecialchars($row["gender"]) . "</p>
				  	<p>Availability: " . ($row["reserved"] == 1 ? "Reserved" : "Available") . "</p>
				</div>";	// close .itembox
		}
		echo "</div>"; // close .item-container
	}
	else {
		echo "<p>No items found.</p>";
	}

echo $footer->render();
?>
	<script src="js/hamburger.js" defer></script>
</body>
</html>