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
<div class="event_page">
    <h1><?php echo $event['titlu']; ?></h1>
    <p><?php echo $event['descriere']; ?></p>
    <p><?php echo $event['data']; ?> at <?php echo $event['ora']; ?></p>
    <p>Location: <?php echo $event['locatie']; ?></p>
    <form action="cart.php" method="post" class="addcart">
    <input type="hidden" name="ticket_id" value="<?php echo $event['id']; ?>">
    <input type="submit" class="smallbtn" value="Adaugă în coș">
    </form>
</div>