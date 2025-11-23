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

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Custom navbar links for admin
$adminNavLinks = [
    'Home' => ['url' => '../index.php', 'icon' => 'fas fa-home'],   // Want to change this to 'Admin Dashboard' ../admin/admin.php
    'Closet' => ['url' => '../closet.php', 'icon' => 'fa fa-list'],
    'Users' => ['url' => 'users.php', 'icon' => 'fas fa-users'],
    'Logout' => ['url' => '../login/logout.php', 'icon' => 'fas fa-sign-out-alt']
];

$navbar = new Navbar($adminNavLinks);
$footer = new Footer("Jay's Closet - Add Item");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item - Jay's Closet</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .add-item-container {
            max-width: 700px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .add-item-container h2 {
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
            text-align: left;
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
        <h1>Add New Item</h1>
    </div>

    <div class="add-item-container">
        <?php
        if (isset($_SESSION["error_message"])) {
            echo '<div class="message-error">' . htmlspecialchars($_SESSION["error_message"]) . '</div>';
            unset($_SESSION["error_message"]);
        }
        ?>

        <h2>Add Item to Inventory</h2>

        <form action="process_add_item.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-group">
                <label for="itemID">Item ID: <span class="required">*</span></label>
                <input type="text" id="itemID" name="itemID" required maxlength="50" 
                       pattern="[A-Za-z0-9\-_]+" title="Letters, numbers, hyphens, and underscores only">
                <p class="help-text">Unique identifier for this item (e.g., "SHIRT-001")</p>
            </div>

            <div class="form-group">
                <label for="description">Description: <span class="required">*</span></label>
                <textarea id="description" name="description" required maxlength="255"></textarea>
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="color">Color: <span class="required">*</span></label>
                    <select id="color" name="color" required>
                        <option value="">Select Color</option>
                        <option value="Red">Red</option>
                        <option value="Yellow">Yellow</option>
                        <option value="Pink">Pink</option>
                        <option value="Blue">Blue</option>
                        <option value="Purple">Purple</option>
                        <option value="Orange">Orange</option>
                        <option value="Green">Green</option>
                        <option value="Beige">Beige</option>
                        <option value="Brown">Brown</option>
                        <option value="Black">Black</option>
                        <option value="White">White</option>
                        <option value="Grey">Grey</option>
                        <option value="Multi-color">Multi-color</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="size">Size: <span class="required">*</span></label>
                    <select id="size" name="size" required>
                        <option value="">Select Size</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                        <option value="One Size">One Size</option>
                        <option value="Custom">Custom</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gender">Gender: <span class="required">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Women">Women</option>
                        <option value="Men">Men</option>
                        <option value="Unisex">Unisex</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="categories">Category: <span class="required">*</span></label>
                    <select id="categories" name="categories" required>
                        <option value="">Select Category</option>
                        <option value="Tops/Blouse">Tops/Blouse</option>
                        <option value="Sweater/Vest/Cardigan">Sweater/Vest/Cardigan</option>
                        <option value="Jacket">Jacket</option>
                        <option value="Bottoms">Bottoms</option>
                        <option value="Shoes">Shoes</option>
                        <option value="Dress">Dress</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Item Images:</label>
                <div class="file-upload-area" id="dropArea">
                    <p>Click to browse or drag and drop images here</p>
                    <input type="file" id="itemImages" name="itemImages[]" accept="image/png,image/jpeg,image/jpg" multiple style="display: none;">
                    <p class="help-text">Upload up to 5 images (PNG or JPG)</p>
                </div>
                <div id="file-list"></div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Add Item</button>
                <a href="../closet.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <?php echo $footer->render(); ?>

    <script src="../js/hamburger.js" defer></script>
    <script>
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('itemImages');
        const fileList = document.getElementById('file-list');
        let selectedFiles = [];

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