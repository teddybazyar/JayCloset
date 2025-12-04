<?php
/**
 * Image Helper Functions
 * Utility functions for handling image uploads and conversions
 */

/**
 * Create an image resource from a file based on its extension
 * 
 * @param string $filePath Path to the image file
 * @param string $extension File extension (jpg, jpeg, png, gif, etc.)
 * @return resource|false Image resource on success, false on failure
 */
function imageCreateFromFile($filePath, $extension) {
    $extension = strtolower($extension);
    
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            return @imagecreatefromjpeg($filePath);
            
        case 'png':
            return @imagecreatefrompng($filePath);
            
        case 'gif':
            return @imagecreatefromgif($filePath);
            
        case 'webp':
            if (function_exists('imagecreatefromwebp')) {
                return @imagecreatefromwebp($filePath);
            }
            return false;
            
        case 'bmp':
            if (function_exists('imagecreatefrombmp')) {
                return @imagecreatefrombmp($filePath);
            }
            return false;
            
        default:
            return false;
    }
}

/**
 * Convert and save an uploaded image to PNG format
 * 
 * @param string $sourcePath Path to the source image file
 * @param string $destinationPath Path where the PNG should be saved
 * @param string $extension Original file extension
 * @return bool True on success, false on failure
 */
function convertImageToPng($sourcePath, $destinationPath, $extension) {
    // Create image resource from file
    $image = imageCreateFromFile($sourcePath, $extension);
    
    if (!$image) {
        return false;
    }
    
    // Preserve transparency for PNG and GIF
    if (in_array(strtolower($extension), ['png', 'gif'])) {
        imagealphablending($image, false);
        imagesavealpha($image, true);
    }
    
    // Save as PNG
    $success = imagepng($image, $destinationPath, 9); // 9 = maximum compression
    
    // Free up memory
    imagedestroy($image);
    
    return $success;
}

/**
 * Process and upload multiple images for an item
 * 
 * @param array $filesArray $_FILES array for the uploaded images
 * @param string $itemID The item ID for directory naming
 * @param int $startingNumber The starting number for image naming (default 1)
 * @param int $maxImages Maximum number of images to process (default 5)
 * @return array Array with 'success' count and 'errors' array
 */
function processItemImages($filesArray, $itemID, $startingNumber = 1, $maxImages = 5) {
    $result = [
        'success' => 0,
        'errors' => []
    ];
    
    // Validate input
    if (!isset($filesArray['name']) || empty($filesArray['name'][0])) {
        return $result;
    }
    
    // Create upload directory
    $uploadDir = "../../images/items/" . $itemID . "/";
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $result['errors'][] = "Failed to create image directory";
            return $result;
        }
    }
    
    $totalFiles = count($filesArray['name']);
    $imageNumber = $startingNumber;
    
    for ($i = 0; $i < $totalFiles && $i < $maxImages; $i++) {
        // Check for upload errors
        if ($filesArray['error'][$i] !== UPLOAD_ERR_OK) {
            $result['errors'][] = "Upload error for file " . ($i + 1);
            continue;
        }
        
        $tmpName = $filesArray['tmp_name'][$i];
        $originalName = $filesArray['name'][$i];
        $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        
        // Validate file type
        if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $result['errors'][] = "Invalid file type for: " . htmlspecialchars($originalName);
            continue;
        }
        
        // Create destination filename
        $newFileName = $itemID . "-" . $imageNumber . ".png";
        $destination = $uploadDir . $newFileName;
        
        // Convert and save as PNG
        if (convertImageToPng($tmpName, $destination, $fileExtension)) {
            $result['success']++;
            $imageNumber++;
        } else {
            $result['errors'][] = "Failed to process: " . htmlspecialchars($originalName);
        }
    }
    
    return $result;
}

/**
 * Get the next available image number for an item
 * 
 * @param string $itemID The item ID
 * @param int $maxCheck Maximum number to check (default 10)
 * @return int The next available image number
 */
function getNextImageNumber($itemID, $maxCheck = 10) {
    $uploadDir = "../../images/items/" . $itemID . "/";
    
    for ($i = 1; $i <= $maxCheck; $i++) {
        $checkPath = $uploadDir . $itemID . "-" . $i . ".png";
        if (!file_exists($checkPath)) {
            return $i;
        }
    }
    
    return $maxCheck + 1;
}

/**
 * Validate image file before upload
 * 
 * @param array $file Single file from $_FILES array
 * @param int $maxSizeBytes Maximum file size in bytes (default 5MB)
 * @return array Array with 'valid' boolean and 'error' message
 */
function validateImageFile($file, $maxSizeBytes = 5242880) {
    $result = ['valid' => true, 'error' => ''];
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        $result['valid'] = false;
        $result['error'] = "Invalid file upload";
        return $result;
    }
    
    // Check file size
    if ($file['size'] > $maxSizeBytes) {
        $result['valid'] = false;
        $result['error'] = "File size exceeds " . ($maxSizeBytes / 1024 / 1024) . "MB limit";
        return $result;
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        $result['valid'] = false;
        $result['error'] = "Invalid file type. Only JPG, PNG, and GIF are allowed";
        return $result;
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mimeType, $allowedMimes)) {
        $result['valid'] = false;
        $result['error'] = "Invalid file type detected";
        return $result;
    }
    
    return $result;
}
?>