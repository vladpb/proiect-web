<?php
session_start();
// Verificăm dacă utilizatorul este autentificat și are rol de admin
if (!isset($_SESSION['username']) || $_SESSION['esteAdministrator'] != 1) {
    $_SESSION['msg'] = "Trebuie să te loghezi ca admin pentru a vedea această pagină";
    header('location: login.php');
    exit;
}

// Conectare la baza de date
$db = mysqli_connect('localhost', 'root', '', 'events');

// Verificăm dacă ID-ul evenimentului este în URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Selectăm datele evenimentului din baza de date
    $query = "SELECT * FROM evenimente WHERE id='$id'";
    $result = mysqli_query($db, $query);
    $event = mysqli_fetch_assoc($result);
}

// Procesăm formularul de actualizare
if (isset($_POST['update_event'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $titlu = mysqli_real_escape_string($db, $_POST['titlu']);
    $descriere = mysqli_real_escape_string($db, $_POST['descriere']);
    $data = mysqli_real_escape_string($db, $_POST['data']);
    $ora = mysqli_real_escape_string($db, $_POST['ora']);
    $locatie = mysqli_real_escape_string($db, $_POST['locatie']);

    // Update în baza de date
    $query = "UPDATE evenimente SET titlu='$titlu', descriere='$descriere', data='$data', ora='$ora', locatie='$locatie' WHERE id='$id'";
    mysqli_query($db, $query);
    $_SESSION['success'] = "Eveniment actualizat cu succes!";
    header('location: admin_panel.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editare Eveniment</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
    <h2>Editare Eveniment</h2>
</div>

<form method="post" action="edit_event.php">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="input-group">
        <label>Titlu:</label>
        <input type="text" name="titlu" value="<?php echo $event['titlu']; ?>">
    </div>
    <div class="input-group">
        <label>Descriere:</label>
        <textarea name="descriere"><?php echo $event['descriere']; ?></textarea>
    </div>
    <div class="input-group">
        <label>Data:</label>
        <input type="date" name="data" value="<?php echo $event['data']; ?>">
    </div>
    <div class="input-group">
        <label>Ora:</label>
        <input type="time" name="ora" value="<?php echo $event['ora']; ?>">
    </div>
    <div class="input-group">
        <label>Locație:</label>
        <input type="text" name="locatie" value="<?php echo $event['locatie']; ?>">
    </div>
    <div class="input-group">
        <button type="submit" class="btn" name="update_event">Salvează modificările</button>
    </div>
</form>

</body>
</html>
