<?php
require "KULSO/config.php";

session_start();

if (!isset($_COOKIE['userid']) || !isset($_POST['product_id'])) {
    header("Location: index.php");
    exit();
}

$userid = (int)$_COOKIE['userid'];
$productid = (int)$_POST['product_id'];

if (!$userid) {
    header("Location: login.php");
    exit();
}

// Check if already favorite
$check_fav = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
$check_fav->bind_param("ii", $userid, $productid);
$check_fav->execute();
$check_result = $check_fav->get_result();

if ($check_result->num_rows > 0) {
    // Remove from favorites
    $delete_fav = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $delete_fav->bind_param("ii", $userid, $productid);
    $delete_fav->execute();
} else {
    // Add to favorites
    $insert_fav = $conn->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $insert_fav->bind_param("ii", $userid, $productid);
    $insert_fav->execute();

    // Get product owner and product name
    $product_query = $conn->prepare("SELECT name, userid FROM products WHERE id = ?");
    $product_query->bind_param("i", $productid);
    $product_query->execute();
    $product = $product_query->get_result()->fetch_assoc();

    if ($product) {
        $owner_id = $product['userid'];
        $product_name = $product['name'];

        // Get username of who favorited
        $user_query = $conn->prepare("SELECT username FROM users WHERE id = ?");
        $user_query->bind_param("i", $userid);
        $user_query->execute();
        $user = $user_query->get_result()->fetch_assoc();

        if ($user) {
            $from_username = $user['username'];
            $message = "$from_username a(z) '$product_name' termékedet kedvencekhez adta.";
            $type = "favorite";
            $created_at = date("Y-m-d H:i:s");
            $seen = 0;

            // Insert notification
            $notif_insert = $conn->prepare("INSERT INTO notifications (fromid, toid, productid, type, message, created_at, seen) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $notif_insert->bind_param("iissssi", $userid, $owner_id, $productid, $type, $message, $created_at, $seen);
            $notif_insert->execute();
        }
    }
}

header("Location: product-details.php?productid=$productid");
exit();
?>