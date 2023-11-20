<?php
include ('server.php');
include('header.php');
$db = mysqli_connect('localhost', 'root', '', 'events');
$query = "SELECT * FROM evenimente";
$result = mysqli_query($db, $query);
?>

<head>
    <title>Evenimente</title>
</head>

<div class="events">
    <?php while ($event = mysqli_fetch_assoc($result)) : ?>
        <div class="event">
            <h2><?php echo $event['titlu']; ?></h2>
            <p><?php echo $event['descriere']; ?></p>
            <p><?php echo $event['data']; ?> at <?php echo $event['ora']; ?></p>
            <p>Location: <?php echo $event['locatie']; ?></p>
            <form action="cart.php" method="post" class="addcart">
            <input type="hidden" name="ticket_id" value="<?php echo $event['id']; ?>">
            <input type="submit" class="smallbtn" value="Adaugă în coș">
            </form>
        </div>
    <?php endwhile; ?>
</div>