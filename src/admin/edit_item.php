<?php
// Check if session is already started, if not start it with secure settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0);
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// Session timeout check (30 minutes)
$timeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: ../index.php?page=login");
    exit;
}
$_SESSION['last_activity'] = time();

// Check if user is logged in and is an admin
if (!isset($_SESSION["LoginStatus"]) || $_SESSION["LoginStatus"] != "YES" || !isset($_SESSION["ADMIN"]) || $_SESSION["ADMIN"] != 1) {
    header("Location: ../index.php?page=login");
    exit;
}

require_once "../includes/Navbar.php";
require_once "../includes/Footer.php";
require_once "../includes/db_cred.php";
require_once "../includes/jayclosetdb.php";

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get item ID from URL
$itemID = isset($_GET['id']) ? trim($_GET['id']) : null;

if (!$itemID) {
    $_SESSION['error_message'] = "Invalid item ID.";
    header("Location: ../closet.php");
    exit;
}

// Fetch item data
$sql = "SELECT * FROM descript WHERE itemID = :id";
$params = [":id" => $itemID];
$items = jayclosetdb::getDataFromSQL($sql, $params);

if (!is_array($items) || count($items) === 0) {
    $_SESSION['error_message'] = "Item not found.";
    header("Location: ../closet.php");
    exit;
}

$item = $items[0];

// Custom navbar links for admin
$adminNavLinks = [
    'Home' => ['url' => '../index.php', 'icon' => 'fas fa-home'],
    'Closet' => ['url' => '../closet.php', 'icon' => 'fa fa-list'],
    'Users' => ['url' => 'users.php', 'icon' => 'fas fa-users'],
    'Logout' => ['url' => '../login/logout.php', 'icon' => 'fas fa-sign-out-alt']
];

$navbar = new Navbar($adminNavLinks);
$footer = new Footer("Jay's Closet - Edit Item");

// Check for existing images
$imageDir = "../../images/items/" . $itemID . "/";
$existingImages = [];

// Check if directory exists and look for images
if (is_dir($imageDir)) {
    // Look for numbered images (itemID-1.png, itemID-2.png, etc.)
    for ($i = 1; $i <= 5; $i++) {
        $imagePath = $imageDir . $itemID . "-" . $i . ".png";
        if (file_exists($imagePath)) {
            $existingImages[] = ['num' => $i, 'path' => $imagePath];
        }
        // Also check for jpg
        $imagePathJpg = $imageDir . $itemID . "-" . $i . ".jpg";
        if (file_exists($imagePathJpg)) {
            $existingImages[] = ['num' => $i, 'path' => $imagePathJpg];
        }
    }
    
    // Also scan the directory for any other image files
    $files = scandir($imageDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && preg_match('/\.(png|jpg|jpeg)$/i', $file)) {
            $fullPath = $imageDir . $file;
            // Check if we already have this file
            $alreadyAdded = false;
            foreach ($existingImages as $img) {
                if ($img['path'] === $fullPath) {
                    $alreadyAdded = true;
                    break;
                }
            }
            if (!$alreadyAdded) {
                $existingImages[] = ['num' => 'other', 'path' => $fullPath, 'filename' => $file];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item - Jay's Closet</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .edit-item-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .edit-item-container h2 {
            margin-bottom: 1.5rem;
            color: #2d3436;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #b8d4e8;
            box-shadow: 0 0 5px rgba(184, 212, 232, 0.5);
            outline: none;
        }

        .form-group input:disabled {
            background-color: #f1f3f5;
            cursor: not-allowed;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s ease;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: #b8d4e8;
            color: #2d3436;
        }

        .btn-primary:hover {
            background: #9fc5db;
        }

        .btn-secondary {
            background: #636e72;
            color: white;
        }

        .btn-secondary:hover {
            background: #4a5256;
        }

        .required {
            color: #dc3545;
        }

        .help-text {
            font-size: 0.85rem;
            color: #636e72;
            margin-top: 0.25rem;
        }

        .images-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #dfe6e9;
        }

        .existing-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .image-item {
            position: relative;
            border: 2px solid #dfe6e9;
            border-radius: 8px;
            overflow: hidden;
        }

        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .image-item .image-label {
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .image-item .delete-image {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            cursor: pointer;
            font-size: 0.75rem;
        }

        .image-item .delete-image:hover {
            background: #c82333;
        }

        .no-images {
            text-align: center;
            color: #636e72;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .upload-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #dfe6e9;
        }

        .upload-section h4 {
            margin-bottom: 1rem;
            color: #2d3436;
        }

        .file-upload-area {
            border: 2px dashed #dfe6e9;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: #b8d4e8;
            background: #f8f9fa;
        }

        #file-list {
            margin-top: 1rem;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 0.5rem;
        }

        .file-item button {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .file-item button:hover {
            background: #c82333;
        }

        .message-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #28a745;
        }

        .message-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <?php $navbar->display(); ?>

    <div class="h1">
        <h1>Edit Item</h1>
    </div>

    <div class="edit-item-container">
        <?php
        if (isset($_SESSION["success_message"])) {
            echo '<div class="message-success">' . htmlspecialchars($_SESSION["success_message"]) . '</div>';
            unset($_SESSION["success_message"]);
        }
        
        if (isset($_SESSION["error_message"])) {
            echo '<div class="message-error">' . htmlspecialchars($_SESSION["error_message"]) . '</div>';
            unset($_SESSION["error_message"]);
        }
        ?>

        <h2>Edit Item Information</h2>

        <form action="process_edit_item.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="original_itemID" value="<?php echo htmlspecialchars($item['itemID']); ?>">

            <div class="form-group">
                <label for="itemID">Item ID:</label>
                <input type="text" id="itemID" name="itemID" value="<?php echo htmlspecialchars($item['itemID']); ?>" disabled>
                <p class="help-text">Item ID cannot be changed</p>
            </div>

            <div class="form-group">
                <label for="description">Description: <span class="required">*</span></label>
                <textarea id="description" name="description" required maxlength="255"><?php echo htmlspecialchars($item['description_of_item']); ?></textarea>
            </div>

            <div class="form-row">
            <div class="form-group">
                    <label for="color">Color: <span class="required">*</span></label>
                    <select id="color" name="color" required>
                        <option value="">Select Color</option>
                        <option value="Red" <?php echo ($item['color'] === 'Red') ? 'selected' : ''; ?>>Red</option>
                        <option value="Yellow" <?php echo ($item['color'] === 'Yellow') ? 'selected' : ''; ?>>Yellow</option>
                        <option value="Pink" <?php echo ($item['color'] === 'Pink') ? 'selected' : ''; ?>>Pink</option>
                        <option value="Blue" <?php echo ($item['color'] === 'Blue') ? 'selected' : ''; ?>>Blue</option>
                        <option value="Purple" <?php echo ($item['color'] === 'Purple') ? 'selected' : ''; ?>>Purple</option>
                        <option value="Orange" <?php echo ($item['color'] === 'Orange') ? 'selected' : ''; ?>>Orange</option>
                        <option value="Green" <?php echo ($item['color'] === 'Green') ? 'selected' : ''; ?>>Green</option>
                        <option value="Beige" <?php echo ($item['color'] === 'Beige') ? 'selected' : ''; ?>>Beige</option>
                        <option value="Brown" <?php echo ($item['color'] === 'Brown') ? 'selected' : ''; ?>>Brown</option>
                        <option value="Black" <?php echo ($item['color'] === 'Black') ? 'selected' : ''; ?>>Black</option>
                        <option value="White" <?php echo ($item['color'] === 'White') ? 'selected' : ''; ?>>White</option>
                        <option value="Grey" <?php echo ($item['color'] === 'Grey') ? 'selected' : ''; ?>>Grey</option>
                        <option value="Multi-color" <?php echo ($item['color'] === 'Multi-color') ? 'selected' : ''; ?>>Multi-color</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="size">Size: <span class="required">*</span></label>
                    <select id="size" name="size" required>
                        <option value="">Select Size</option>
                        <option value="XS" <?php echo ($item['size'] === 'XS') ? 'selected' : ''; ?>>XS</option>
                        <option value="S" <?php echo ($item['size'] === 'S') ? 'selected' : ''; ?>>S</option>
                        <option value="M" <?php echo ($item['size'] === 'M') ? 'selected' : ''; ?>>M</option>
                        <option value="L" <?php echo ($item['size'] === 'L') ? 'selected' : ''; ?>>L</option>
                        <option value="XL" <?php echo ($item['size'] === 'XL') ? 'selected' : ''; ?>>XL</option>
                        <option value="XXL" <?php echo ($item['size'] === 'XXL') ? 'selected' : ''; ?>>XXL</option>
                        <option value="One Size" <?php echo ($item['size'] === 'One Size') ? 'selected' : ''; ?>>One Size</option>
                        <option value="Custom" <?php echo ($item['size'] === 'Custom') ? 'selected' : ''; ?>>Custom</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gender">Gender: <span class="required">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Women" <?php echo ($item['gender'] === 'Women') ? 'selected' : ''; ?>>Women</option>
                        <option value="Men" <?php echo ($item['gender'] === 'Men') ? 'selected' : ''; ?>>Men</option>
                        <option value="Unisex" <?php echo ($item['gender'] === 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="categories">Category: <span class="required">*</span></label>
                    <select id="categories" name="categories" required>
                        <option value="">Select Category</option>
                        <option value="Tops/Blouse" <?php echo ($item['categories'] === 'Tops/Blouse') ? 'selected' : ''; ?>>Tops/Blouse</option>
                        <option value="Sweater/Vest/Cardigan" <?php echo ($item['categories'] === 'Sweater/Vest/Cardigan') ? 'selected' : ''; ?>>Sweater/Vest/Cardigan</option>
                        <option value="Jacket" <?php echo ($item['categories'] === 'Jacket') ? 'selected' : ''; ?>>Jacket</option>
                        <option value="Bottoms" <?php echo ($item['categories'] === 'Bottoms') ? 'selected' : ''; ?>>Bottoms</option>
                        <option value="Shoes" <?php echo ($item['categories'] === 'Shoes') ? 'selected' : ''; ?>>Shoes</option>
                        <option value="Dress" <?php echo ($item['categories'] === 'Dress') ? 'selected' : ''; ?>>Dress</option>
                        <option value="Others" <?php echo ($item['categories'] === 'Others') ? 'selected' : ''; ?>>Others</option>
                    </select>
                </div>
            </div>

            <div class="images-section">
                <h3>Item Images</h3>
                <?php if (!empty($existingImages)): ?>
                    <div class="existing-images">
                        <?php foreach ($existingImages as $image): ?>
                            <div class="image-item">
                                <?php 
                                    $displayPath = $image['path'];
                                    $imageLabel = isset($image['filename']) ? $image['filename'] : "Image " . $image['num'];
                                ?>
                                <span class="image-label"><?php echo htmlspecialchars($imageLabel); ?></span>
                                <img src="<?php echo htmlspecialchars($displayPath); ?>" alt="Item Image">
                                <button type="button" class="delete-image" onclick="deleteImage('<?php echo htmlspecialchars($itemID); ?>', '<?php echo isset($image['filename']) ? htmlspecialchars($image['filename']) : $image['num']; ?>')">Delete</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-images">
                        <p>No images uploaded for this item</p>
                    </div>
                <?php endif; ?>

                <!-- Image Upload Section -->
                <div class="upload-section">
                    <h4>Upload New Images</h4>
                    <form action="upload_image.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($itemID); ?>">
                        
                        <div class="file-upload-area" id="dropArea">
                            <p>Click to browse or drag and drop images here</p>
                            <input type="file" id="itemImages" name="itemImages[]" accept="image/png,image/jpeg,image/jpg" multiple style="display: none;">
                            <p class="help-text">Upload up to 5 images (PNG or JPG)</p>
                        </div>
                        <div id="file-list"></div>
                        
                        <button type="submit" class="btn btn-upload" style="margin-top: 1rem; background: #28a745; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Upload Images</button>
                    </form>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="../closet.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <?php echo $footer->render(); ?>

    <script src="../js/hamburger.js" defer></script>
    <script>
        function deleteImage(itemId, imageIdentifier) {
            if (confirm('Are you sure you want to delete this image?')) {
                window.location.href = 'delete_image.php?id=' + itemId + '&img=' + encodeURIComponent(imageIdentifier);
            }
        }

        // File upload handling
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('itemImages');
        const fileList = document.getElementById('file-list');
        let selectedFiles = [];

        if (dropArea) {
            dropArea.addEventListener('click', () => fileInput.click());

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, e => { e.preventDefault(); e.stopPropagation(); }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.style.borderColor = '#b8d4e8', false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.style.borderColor = '#dfe6e9', false);
            });

            dropArea.addEventListener('drop', e => handleFiles(e.dataTransfer.files), false);
            fileInput.addEventListener('change', e => handleFiles(e.target.files), false);
        }

        function handleFiles(files) {
            files = [...files];
            if (selectedFiles.length + files.length > 5) {
                alert('You can only upload up to 5 images');
                return;
            }
            files.forEach(file => {
                if (file.type.match('image.*')) {
                    selectedFiles.push(file);
                    displayFile(file);
                }
            });
            updateFileInput();
        }

        function displayFile(file) {
            const div = document.createElement('div');
            div.className = 'file-item';
            div.innerHTML = `<span>${file.name}</span><button type="button" onclick="removeFile('${file.name}')">Remove</button>`;
            fileList.appendChild(div);
        }

        function removeFile(fileName) {
            selectedFiles = selectedFiles.filter(f => f.name !== fileName);
            updateFileInput();
            fileList.innerHTML = '';
            selectedFiles.forEach(f => displayFile(f));
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            fileInput.files = dt.files;
        }
    </script>
</body>
</html>