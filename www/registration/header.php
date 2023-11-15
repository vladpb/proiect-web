<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <a href="index.php">Acasă</a>
        <a href="events.php">Evenimente</a>
        <?php if (!isset($_SESSION['username'])) : ?>
            <a href="login.php">Logare</a>
        <?php else : ?>
            <a href="admin_panel.php?logout='1'">Logout</a>
            <?php if ($_SESSION['esteAdministrator'] == 1) : ?>
                <a href="admin_panel.php">Panou Administrator</a>
            <?php endif ?>
        <?php endif ?>
    </div>