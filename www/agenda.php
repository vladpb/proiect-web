<?php
include('server.php');
include('header.php');
$db = mysqli_connect('localhost', 'root', '', 'events');
$query = "SELECT * FROM agenda";
$result = mysqli_query($db, $query);
?>

<head>
    <title>Agenda</title>
</head>

<div class="events">
    <?php while ($event = mysqli_fetch_assoc($result)) : ?>
        <div class="event">
            <h2><?php echo $event['titlu_activitate']; ?></h2>
            <p><?php echo $event['descriere']; ?></p>
            <p><?php echo $event['ora_start']; ?></p>
            <p><?php echo $event['ora_final']; ?></p>
<!--            --><?php //if (isset($_SESSION['esteAdministrator']) && $_SESSION['esteAdministrator'] == 1) : ?>
<!--                <form method="post" action="edit_activity.php">-->
<!--                    <input type="hidden" name="activitate_id" value="--><?php //echo $event['id']; ?><!--">-->
<!--                    <button type="submit" name="edit_activitate">Editează</button>-->
<!--                </form>-->
<!--                <form method="post" action="delete_activity.php">-->
<!--                    <input type="hidden" name="activitate_id" value="--><?php //echo $event['id']; ?><!--">-->
<!--                    <button type="submit" name="delete_activitate">Șterge</button>-->
<!--                </form>-->
<!--            --><?php //endif; ?>
        </div>
    <?php endwhile; ?>
</div>