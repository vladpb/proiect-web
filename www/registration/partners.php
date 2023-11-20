<?php
include ('server.php');
include('header.php');
$db = mysqli_connect('localhost', 'root', '', 'events');
$query = "SELECT * FROM parteneri";
$result = mysqli_query($db, $query);

?>

<head>
    <title>Parteneri</title>
</head>

<div class="events">
    <?php while ($event = mysqli_fetch_assoc($result)) : ?>
        <div class="event">
            <h2><?php echo $event['nume']; ?></h2>
            <p><?php echo $event['descriere']; ?></p>
        </div>
    <?php endwhile; ?>
</div>