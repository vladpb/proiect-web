<?php
session_start();

// Elimină biletul din coș
if (isset($_POST['remove']) && isset($_POST['ticket_id'])) {
    $ticketId = $_POST['ticket_id'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if (is_array($item) && isset($item['id']) && $item['id'] == $ticketId) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

header('Location: cart.php');
?>
