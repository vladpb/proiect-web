<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">Acasă</a>
        <a href="events.php">Evenimente</a>
        <a href="agenda.php">Agenda</a>
        <a href="speakers.php">Speakeri</a>
        <a href="partners.php">Parteneri</a>
        <a href="contact.php">Contact</a>
        <?php if (!isset($_SESSION['username'])) : ?>
            <a href="login.php">Logare</a>
        <?php else : ?>
            <a href="index.php?logout='1'">Logout</a>
            <?php if (isset($_SESSION['esteAdministrator']) && $_SESSION['esteAdministrator'] == 1) : ?>
                <a href="admin_panel.php">Panou Administrator</a>
            <?php endif ?>
        <?php endif ?>
        <a href="cart.php" >Cos</a>
    </div>