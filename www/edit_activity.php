<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['esteAdministrator'] != 1) {
    $_SESSION['msg'] = "Trebuie să te loghezi ca admin pentru a vedea această pagină";
    header('location: login.php');
    exit;
}

$db = mysqli_connect('localhost', 'root', '', 'events');

$activitate = array('titlu_activitate' => '', 'descriere' => '', 'ora_start' => '', 'ora_final' => '');

$currentPartners = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM agenda WHERE id='$id'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $activitate = mysqli_fetch_assoc($result);

        $currentPartnersQuery = "SELECT partener_id FROM parteneri_activitati WHERE eveniment_id='$id'";
        $currentPartnersResult = mysqli_query($db, $currentPartnersQuery);
        while ($row = mysqli_fetch_assoc($currentPartnersResult)) {
            $currentPartners[] = $row['partener_id'];
        }
    } else {
        header('location: admin_panel.php');
        exit;
    }
}

if (isset($_POST['update_activitate'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $titlu_activitate = mysqli_real_escape_string($db, $_POST['titlu_activitate']);
    $descriere = mysqli_real_escape_string($db, $_POST['descriere']);
    $ora_start = mysqli_real_escape_string($db, $_POST['ora_start']);
    $ora_final = mysqli_real_escape_string($db, $_POST['ora_final']);
    $partener_id = mysqli_real_escape_string($db, $_POST['partener_id']);


    $query = "UPDATE agenda SET titlu_activitate='$titlu_activitate', descriere='$descriere', ora_start='$ora_start', ora_final='$ora_final', partener_id='$partener_id' WHERE id='$id'";
    mysqli_query($db, $query);

    $submittedPartners = isset($_POST['parteneri']) ? $_POST['parteneri'] : [];
    
    $partnersToAdd = array_diff($submittedPartners, $currentPartners);
    $partnersToRemove = array_diff($currentPartners, $submittedPartners);

    foreach ($partnersToAdd as $partener_id) {
        $query_insert_partener_activitate = "INSERT INTO parteneri_activitati (eveniment_id, partener_id) VALUES ('$id', '$partener_id')";
        mysqli_query($db, $query_insert_partener_activitate);
    }

    foreach ($partnersToRemove as $partener_id) {
        $query_delete_partener = "DELETE FROM parteneri_activitati WHERE eveniment_id='$id' AND partener_id='$partener_id'";
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
    <label>Partener:</label>
    <select name="partener_id" required>
        <?php
        $partenerQuery = "SELECT id, nume FROM parteneri";
        $partenerResult = mysqli_query($db, $partenerQuery);

        while ($partener = mysqli_fetch_assoc($partenerResult)) {
            $selected = ($partener['id'] == $activitate['partener_id']) ? "selected" : "";
            echo "<option value='" . $partener['id'] . "' $selected>" . htmlspecialchars($partener['nume']) . "</option>";
        }
        ?>
    </select>
</div>    <div class="input-group">
        <button type="submit" class="btn" name="update_activitate">Salvează modificările</button>
    </div>
</form>

</body>
</html>
