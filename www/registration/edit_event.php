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
    $contact = mysqli_real_escape_string($db, $_POST['mail']);
    $speaker_id = mysqli_real_escape_string($db, $_POST['speaker_id']);
    $pret_bilet = mysqli_real_escape_string($db, $_POST['pret_bilet']);

    // Update în baza de date, incluzând și speakerul și contactul
    $query = "UPDATE evenimente SET titlu='$titlu', descriere='$descriere', data='$data', ora='$ora', locatie='$locatie', mail='$contact', speaker_id='$speaker_id', pret_bilet='$pret_bilet' WHERE id='$id'";
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
        <label>Contact:</label>
        <input type="email" name="mail" value="<?php echo $event['mail']; ?>">
    </div>
    <div class="input-group">
        <label>Speaker:</label>
        <select name="speaker_id" required>
            <?php
            // Obțineți lista de speakeri din baza de date
            $speakerQuery = "SELECT id, nume FROM speakeri";
            $speakerResult = mysqli_query($db, $speakerQuery);

            // Afiseaza fiecare speaker ca o opțiune în meniul derulant
            while ($speaker = mysqli_fetch_assoc($speakerResult)) {
                // Verificați dacă acesta este speakerul curent pentru eveniment
                $selected = ($speaker['id'] == $event['speaker_id']) ? "selected" : "";
                echo "<option value='" . $speaker['id'] . "' $selected>" . $speaker['nume'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="input-group">
        <label>Parteneri:</label>
        <select name="parteneri[]" multiple>
            <?php
            // Obține lista de parteneri din baza de date
            $queryParteneri = "SELECT * FROM parteneri";
            $resultParteneri = mysqli_query($db, $queryParteneri);

            // Afisează opțiunile pentru selecție multiplă
            while ($partener = mysqli_fetch_assoc($resultParteneri)) {
                echo '<option value="' . $partener['id'] . '">' . $partener['nume'] . '</option>';
            }
            ?>
        </select>
    </div>
    <label>Preț Bilet:</label>
    <input type="number" step="0.01" name="pret_bilet" required><br>

    <div class="input-group">
        <button type="submit" class="btn" name="update_event">Salvează modificările</button>
    </div>
</form>

</body>
</html>
