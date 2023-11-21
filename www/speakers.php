<?php
include ('server.php');
include('header.php');

include 'Database.php';

$db = new Database();

$query = "SELECT * FROM speakeri";
$result = $db->query($query);

$db->close();

?>

<head>
    <title>Speakers</title>
</head>

<div class="events">
    <?php while ($event = mysqli_fetch_assoc($result)) : ?>
        <div class="event">
            <h2><?php echo $event['nume']; ?></h2>
            <p><?php echo $event['descriere']; ?></p>
        </div>
    <?php endwhile; ?>
</div>