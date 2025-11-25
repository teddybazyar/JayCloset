<?php

function send_admin_email($orderID, $user, array $items, $orderInfo = null) {
    $adminEmail = "jaycloset@ezdz.com"; // change this email when finding the email to send to

    $subject = "New Order Placed - Order #{$orderID}";

    $message = "New order placed.\r\n\r\n";
    $message .= "Order ID: {$orderID}\r\n";
    $message .= "User ID: " . ($user['ID'] ?? '') . "\r\n";
    $message .= "Name: " . trim(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? '')) . "\r\n";
    $message .= "Email: " . ($user['email'] ?? '') . "\r\n\r\n";

    if (!empty($orderInfo) && isset($orderInfo['expiration'])) {
        $message .= "Expiration: " . date("F j, Y, g:i a", strtotime($orderInfo['expiration'])) . "\r\n\r\n";
    }

    $message .= "Items:\r\n";
    foreach ($items as $it) {
        $message .= "- [ItemID: {$it['itemID']}] SKU: {$it['sku']} - {$it['title']} - {$it['description']}\r\n";
    }

    $message .= "\r\n--\r\nJay Closet System\r\n";

    $headers = "From: noreply@yourdomain.com\r\n";
    $headers .= "Reply-To: noreply@yourdomain.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    @mail($adminEmail, $subject, $message, $headers);
}
?>