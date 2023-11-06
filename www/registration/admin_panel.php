<?php
include('server.php');

if (!isset($_SESSION['username']) || $_SESSION['esteAdministrator'] != 1) {
    $_SESSION['msg'] = "Trebuie sa fii logat ca administator";
    header('location: login.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}

$query = "SELECT * FROM evenimente";
$events = mysqli_query($db, $query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="style.css"
</head>
<body>

<div class="header">
    <h2>Panou Administrator</h2>
</div>
<div class="content">
    <!-- notification message -->
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success" >
            <h3>
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </h3>
        </div>


    <?php endif ?>

    <!-- logged in admin information -->
    <?php  if (isset($_SESSION['username'])) : ?>
        <p>Bun venit, <strong><?php echo $_SESSION['username']; ?></strong></p>
        <!-- You can add more admin functionalities here, like managing events etc. -->

        <p> <a href="admin_panel.php?logout='1'" style="color: red;">Log out</a> </p>
    <?php endif ?>

</div>

<table>
    <h2 class="header">Evenimente</h2>
    <thead>
    <tr>
        <th>Titlu</th>
        <th>Descriere</th>
        <th>Data</th>
        <th>Ora</th>
        <th>Locație</th>
        <th>Acțiuni</th>
    </tr>
    </thead>
    <tbody>
    <?php while($event = mysqli_fetch_assoc($events)): ?>
        <tr>
            <td><?php echo $event['titlu']; ?></td>
            <td><?php echo $event['descriere']; ?></td>
            <td><?php echo $event['data']; ?></td>
            <td><?php echo $event['ora']; ?></td>
            <td><?php echo $event['locatie']; ?></td>
            <td><a href="edit_event.php?id=<?php echo $event['id']; ?>">Edit</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>


    <h2 class="header">Adaugă eveniment</h2>
    <form method="post" action="admin_panel.php">

        <label>Titlu:</label>
        <input type="text" name="titlu" required><br>

        <label>Descriere:</label>
        <textarea name="descriere" required></textarea><br>

        <label>Data:</label>
        <input type="date" name="data" required><br>

        <label>Ora:</label>
        <input type="time" name="ora" required><br>

        <label>Locație:</label>
        <input type="text" name="locatie" required><br>

        <button class="btn" style="width: 30%" type="submit" name="adauga_eveniment">Adaugă</button>
    </form>

</body>
</html>
