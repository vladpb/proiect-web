<?php
include ('server.php');
include('header.php');
$db = mysqli_connect('localhost', 'root', '', 'events');
$query = "SELECT * FROM evenimente";
$result = mysqli_query($db, $query);
?>

<div class="events">
    <?php while ($event = mysqli_fetch_assoc($result)) : ?>
        <div class="event">
            <h2><?php echo $event['titlu']; ?></h2>
            <p><?php echo $event['descriere']; ?></p>
            <p><?php echo $event['data']; ?> at <?php echo $event['ora']; ?></p>
            <p>Location: <?php echo $event['locatie']; ?></p>
        </div>
    <?php endwhile; ?>
</div>