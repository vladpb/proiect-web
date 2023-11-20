<?php
include ('server.php');
include('header.php');
$db = mysqli_connect('localhost', 'root', '', 'events');
$query = "SELECT titlu,mail,locatie FROM evenimente";
$result = mysqli_query($db, $query);
?>

<head>
    <title>Contact</title>
</head>

<div class="events">
    <?php while ($event = mysqli_fetch_assoc($result)) : ?>
        <div class="event">
            <h2><?php echo $event['titlu']; ?></h2>
            <p><?php echo $event['mail']; ?></p>
            <p><?php echo $event['locatie']; ?></p>
        </div>
    <?php endwhile; ?>
</div>