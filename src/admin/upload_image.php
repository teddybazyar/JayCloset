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

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Invalid security token. Please try again.";
    header("Location: ../closet.php");
    exit;
}

// Get item ID
$itemID = isset($_POST['itemID']) ? trim($_POST['itemID']) : null;

if (!$itemID) {
    $_SESSION['error_message'] = "Invalid item ID.";
    header("Location: ../closet.php");
    exit;
}

// Handle image uploads
if (isset($_FILES['itemImages']) && !empty($_FILES['itemImages']['name'][0])) {
    $uploadDir = "../../images/items/" . $itemID . "/";
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Find the next available image number
    $nextNum = 1;
    for ($i = 1; $i <= 10; $i++) {
        $checkPath = $uploadDir . $itemID . "-" . $i . ".png";
        if (!file_exists($checkPath)) {
            $nextNum = $i;
            break;
        }
    }
    
    $uploadedCount = 0;
    $totalFiles = count($_FILES['itemImages']['name']);
    
    for ($i = 0; $i < $totalFiles && $i < 5; $i++) {
        if ($_FILES['itemImages']['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['itemImages']['tmp_name'][$i];
            $fileExtension = strtolower(pathinfo($_FILES['itemImages']['name'][$i], PATHINFO_EXTENSION));
            
            // Validate file type
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                $newFileName = $itemID . "-" . ($nextNum + $uploadedCount) . ".png";
                $destination = $uploadDir . $newFileName;
                
                // Convert to PNG if JPEG
                if (in_array($fileExtension, ['jpg', 'jpeg'])) {
                    $image = imagecreatefromjpeg($tmpName);
                    if ($image) {
                        imagepng($image, $destination);
                        imagedestroy($image);
                        $uploadedCount++;
                    }
                } else {
                    if (move_uploaded_file($tmpName, $destination)) {
                        $uploadedCount++;
                    }
                }
            }
        }
    }
    
    if ($uploadedCount > 0) {
        $_SESSION['success_message'] = $uploadedCount . " image(s) uploaded successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to upload images. Please try again.";
    }
} else {
    $_SESSION['error_message'] = "No images selected for upload.";
}

// Redirect back to edit page
header("Location: edit_item.php?id=" . urlencode($itemID));
exit;
?>