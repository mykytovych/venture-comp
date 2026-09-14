<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['tour_id'])) {
    $tour_id = (int)$_GET['tour_id'];
    if (!in_array($tour_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $tour_id;
    }
}

header('Location: cart.php');
exit();
?>
