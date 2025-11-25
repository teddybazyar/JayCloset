<?php
require_once __DIR__ . '/../includes/jayclosetdb.php';
require_once __DIR__ . '/send_user_email.php';
require_once __DIR__ . '/send_admin_email.php';

$soonRows = jayclosetdb::getDataFromSQL(
    "SELECT o.orderID, o.ID as userID, o.expiration, u.email, u.fname, u.lname
     FROM orders o
     JOIN users u ON o.ID = u.ID
     WHERE o.active_order = 1
       AND o.expiration > NOW()
       AND o.expiration <= DATE_ADD(NOW(), INTERVAL 1 DAY)"
);

foreach ($soonRows as $row) {
    $orderID = $row['orderID'];
    $userEmail = $row['email'];
    $fname = $row['fname'];
    $lname = $row['lname'];

    $subject = "Order #{$orderID} Expiring Soon";
    $message = "Hello {$fname} {$lname},\r\n\r\n";
    $message .= "Your order (#{$orderID}) is expiring on " . date("F j, Y, g:i a", strtotime($row['expiration'])) . ".\r\n";
    $message .= "If you need more time, please contact us.\r\n\r\nRegards,\r\nJay Closet\r\n";

    $headers = "From: noreply@yourdomain.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    @mail($userEmail, $subject, $message, $headers);
}

$expiredOrders = jayclosetdb::getDataFromSQL(
    "SELECT o.orderID, o.ID as userID, o.expiration
     FROM orders o
     WHERE o.active_order = 1
       AND o.expiration <= NOW()"
);

foreach ($expiredOrders as $ord) {
    $orderID = $ord['orderID'];
    $userID = $ord['userID'];
    $expiration = $ord['expiration'];

    // get items for this order
    $items = jayclosetdb::getDataFromSQL("SELECT itemID FROM items WHERE orderID = ?", [$orderID]);
    $itemIDs = array_map(fn($r) => $r['itemID'], $items);

    jayclosetdb::startTransaction();
    try {
        jayclosetdb::executeSQL(
            "INSERT INTO history (orderID, ID, expiration, returned) VALUES (?, ?, ?, ?)",
            [$orderID, $userID, $expiration, 0]
        );

        // unreserve items in descript
        if (!empty($itemIDs)) {
            $placeholders = implode(',', array_fill(0, count($itemIDs), '?'));
            $updateSql = "UPDATE descript SET reserved = 0 WHERE itemID IN ($placeholders)";
            jayclosetdb::executeSQL($updateSql, $itemIDs);
        }

        // delete items rows
        jayclosetdb::executeSQL("DELETE FROM items WHERE orderID = ?", [$orderID]);

        // delete order row (or mark inactive)
        jayclosetdb::executeSQL("DELETE FROM orders WHERE orderID = ?", [$orderID]);

        jayclosetdb::commitTransaction();

        // send expiry email to user
        $userRow = jayclosetdb::getDataFromSQL("SELECT fname, lname, email FROM users WHERE ID = ?", [$userID]);
        if (!empty($userRow)) {
            $u = $userRow[0];
            $subject = "Order #{$orderID} Expired";
            $message = "Hello {$u['fname']} {$u['lname']},\r\n\r\n";
            $message .= "Your order #{$orderID} has expired on " . date("F j, Y, g:i a", strtotime($expiration)) . ".\r\n";
            $message .= "The reserved items have been returned to inventory.\r\n\r\nRegards,\r\nJay Closet\r\n";
            $headers = "From: noreply@yourdomain.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            @mail($u['email'], $subject, $message, $headers);
        }
    } catch (Exception $e) {
        jayclosetdb::rollbackTransaction();
        error_log("Failed processing expired order {$orderID}: " . $e->getMessage());
    }
}

?>