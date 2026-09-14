<?php
session_start();

if (isset($_GET['tour_id'])) {
    $tour_id = (int)$_GET['tour_id'];
    if (($key = array_search($tour_id, $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }
}

header('Location: cart.php');
exit();
?>
