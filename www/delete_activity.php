<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['esteAdministrator'] != 1) {
    $_SESSION['msg'] = "Trebuie să te loghezi ca admin pentru a vedea această pagină";
    header('location: login.php');
    exit;
}

$db = mysqli_connect('localhost', 'root', '', 'events');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM agenda WHERE id='$id'";
    mysqli_query($db, $query);
    $_SESSION['success'] = "Agenda a fost ștearsa cu succes!";
    header('location: admin_panel.php');
}
?>