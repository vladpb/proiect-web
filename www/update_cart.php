<?php
session_start();

if (isset($_POST['update']) && isset($_POST['ticket_id']) && isset($_POST['quantity'])) {
    $ticketId = $_POST['ticket_id'];
    $quantity = intval($_POST['quantity']);

    foreach ($_SESSION['cart'] as $key => $item) {
        if (is_array($item) && isset($item['id']) && $item['id'] == $ticketId) {
            if ($quantity > 0) {
                $_SESSION['cart'][$key]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$key]);
            }
            break;
        }
    }
}

header('Location: cart.php');
?>
