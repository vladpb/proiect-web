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

// Inițializăm variabila $activitate pentru a evita eroarea
$activitate = array('titlu_activitate' => '', 'descriere' => '', 'ora_start' => '', 'ora_final' => '');

// Variabila pentru a stoca partenerii curenti
$currentPartners = [];

// Verificăm dacă ID-ul activității este în URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Selectăm datele activității din baza de date
    $query = "SELECT * FROM agenda WHERE id='$id'";
    $result = mysqli_query($db, $query);

    // Verificăm dacă există date pentru activitate
    if ($result && mysqli_num_rows($result) > 0) {
        $activitate = mysqli_fetch_assoc($result);

        // Fetch currently associated partners
        $currentPartnersQuery = "SELECT partener_id FROM parteneri_activitati WHERE eveniment_id='$id'";
        $currentPartnersResult = mysqli_query($db, $currentPartnersQuery);
        while ($row = mysqli_fetch_assoc($currentPartnersResult)) {
            $currentPartners[] = $row['partener_id'];
        }
    } else {
        // Dacă nu există date pentru activitate, poți redirecționa utilizatorul sau lua alte măsuri
        header('location: admin_panel.php');
        exit;
    }
}

// Procesăm formularul de actualizare
if (isset($_POST['update_activitate'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $titlu_activitate = mysqli_real_escape_string($db, $_POST['titlu_activitate']);
    $descriere = mysqli_real_escape_string($db, $_POST['descriere']);
    $ora_start = mysqli_real_escape_string($db, $_POST['ora_start']);
    $ora_final = mysqli_real_escape_string($db, $_POST['ora_final']);

    // Update în baza de date
    $query = "UPDATE agenda SET titlu_activitate='$titlu_activitate', descriere='$descriere', ora_start='$ora_start', ora_final='$ora_final' WHERE id='$id'";
    mysqli_query($db, $query);

    // Adaugă partenerii selectați - optimizăm logica de actualizare
    $submittedPartners = isset($_POST['parteneri']) ? $_POST['parteneri'] : [];
    
    // Determinăm partenerii care trebuie adăugați și șterși
    $partnersToAdd = array_diff($submittedPartners, $currentPartners);
    $partnersToRemove = array_diff($currentPartners, $submittedPartners);

    // Adaugă partenerii noi
    foreach ($partnersToAdd as $partener_id) {
        $query_insert_partener_activitate = "INSERT INTO eveniment_partener (eveniment_id, partener_id) VALUES ('$id', '$partener_id')";
        mysqli_query($db, $query_insert_partener_activitate);
    }

    // Șterge partenerii care nu mai sunt asociați
    foreach ($partnersToRemove as $partener_id) {
        $query_delete_partener = "DELETE FROM eveniment_partener WHERE eveniment_id='$id' AND partener_id='$partener_id'";
        mysqli_query($db, $query_delete_partener);
    }

    $_SESSION['success'] = "Activitate actualizată cu succes!";
    header('location: admin_panel.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editare Activitate</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
    <h2>Editare Activitate</h2>
</div>

<form method="post" action="edit_activity.php">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="input-group">
        <label>Titlu Activitate:</label>
        <input type="text" name="titlu_activitate" value="<?php echo $activitate['titlu_activitate']; ?>">
    </div>
    <div class="input-group">
        <label>Descriere:</label>
        <textarea name="descriere"><?php echo $activitate['descriere']; ?></textarea>
    </div>
    <div class="input-group">
        <label>Ora de Start</label>
        <input type="time" name="ora_start" value="<?php echo $activitate['ora_start']; ?>">
    </div>
    <div class="input-group">
        <label>Ora de Final</label>
        <input type="time" name="ora_final" value="<?php echo $activitate['ora_final']; ?>">
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
                $selected = in_array($partener['id'], $currentPartners) ? ' selected' : '';
                echo '<option value="' . $partener['id'] . '"' . $selected . '>' . $partener['nume'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="input-group">
        <button type="submit" class="btn" name="update_activitate">Salvează modificările</button>
    </div>
</form>

</body>
</html>
