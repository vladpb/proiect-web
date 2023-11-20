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

// Inițializăm variabila $speaker pentru a evita eroarea
$partener = array('nume' => '', 'descriere' => '');

// Verificăm dacă ID-ul partenerului este în URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Selectăm datele partenerului din baza de date
    $query = "SELECT * FROM parteneri WHERE id='$id'";
    $result = mysqli_query($db, $query);

    // Verificăm dacă există date pentru partener
    if ($result && mysqli_num_rows($result) > 0) {
        $partener = mysqli_fetch_assoc($result);
    } else {
        // Dacă nu există date pentru partener, poți redirecționa utilizatorul sau lua alte măsuri
        header('location: admin_panel.php');
        exit;
    }
}

// Procesăm formularul de actualizare
if (isset($_POST['update_partener'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $nume = mysqli_real_escape_string($db, $_POST['nume']);
    $descriere = mysqli_real_escape_string($db, $_POST['descriere']);

    // Update în baza de date
    $query = "UPDATE parteneri SET nume='$nume', descriere='$descriere' WHERE id='$id'";
    mysqli_query($db, $query);
    $_SESSION['success'] = "Partener actualizat cu succes!";
    header('location: admin_panel.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editare Partener</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
    <h2>Editare Partener</h2>
</div>

<form method="post" action="edit_partener.php">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="input-group">
        <label>Nume:</label>
        <input type="text" name="nume" value="<?php echo $partener['nume']; ?>">
    </div>
    <div class="input-group">
        <label>Descriere:</label>
        <textarea name="descriere"><?php echo $partener['descriere']; ?></textarea>
    </div>
    <div class="input-group">
        <button type="submit" class="btn" name="update_partener">Salvează modificările</button>
    </div>
</form>

</body>
</html>
