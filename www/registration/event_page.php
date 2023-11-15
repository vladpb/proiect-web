<?php
include ('server.php');
include('header.php');
$db = mysqli_connect('localhost', 'root', '', 'events');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM evenimente WHERE id='$id'";
    $result = mysqli_query($db, $query);
    $event = mysqli_fetch_assoc($result);
}
?>
<div class="evenimente">
<div class="event_page">
    <h1><?php echo $event['titlu']; ?></h1>
    <p><?php echo $event['descriere']; ?></p>
    <p><?php echo $event['data']; ?> at <?php echo $event['ora']; ?></p>
    <p>Location: <?php echo $event['locatie']; ?></p>
</div>
</div>