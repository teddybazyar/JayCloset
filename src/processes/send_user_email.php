<?php

function send_user_email($toEmail, $fname, $lname, $orderID, array $items, $orderInfo = null) {
    $subject = "Order Confirmation - Order #{$orderID}";
    $expiresText = '';
    if (!empty($orderInfo) && isset($orderInfo['expiration'])) {
        $expiresText = "Expiration: " . date("F j, Y, g:i a", strtotime($orderInfo['expiration'])) . "\r\n";
    }

    $message = "Hello " . ($fname ?: '') . " " . ($lname ?: '') . ",\r\n\r\n";
    $message .= "Thank you for your order. Your order number is: {$orderID}\r\n";
    $message .= $expiresText . "\r\n";
    $message .= "Items in your order:\r\n";

    foreach ($items as $it) {
        $message .= "- [ItemID: {$it['itemID']}] SKU: {$it['sku']} - {$it['title']} - {$it['description']}\r\n";
    }

    $message .= "\r\nIf you have questions, reply to this email.\r\n\r\nRegards,\r\nJay Closet\r\n";

    // headers
    $headers = "From: noreply@jaycloset.com\r\n";
    $headers .= "Reply-To: noreply@jaycloset.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    @mail($toEmail, $subject, $message, $headers);
}
?>