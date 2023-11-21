<?php
include 'Database.php';
include 'SessionManager.php';
include 'header.php';
$db = new Database();
$session = new SessionManager();


if (!$session->exists('username') || $session->get('esteAdministrator') != 1) {
    $session->set('msg', "Trebuie sa fii logat ca administrator");
    header('location: login.php');
    exit;
}

// Fetch data for display
$eventsQuery = "SELECT * FROM evenimente";
$events = $db->query($eventsQuery);

$speakerQuery = "SELECT * FROM speakeri";
$speakeri = $db->query($speakerQuery);

$partnerQuery = "SELECT * FROM parteneri";
$parteneri = $db->query($partnerQuery);

$activityQuery = "SELECT * FROM agenda";
$activitati = $db->query($activityQuery);

// Function to fetch partners for an activity
function fetchPartnersForActivity($db, $activitate_id) {
    $query = "SELECT p.nume FROM parteneri p
              INNER JOIN parteneri_activitati pa ON p.id = pa.partener_id
              WHERE pa.activitate_id = $activitate_id";
    $result = $db->query($query);
    $partners = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $partners[] = $row['nume'];
    }
    return implode(', ', $partners);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
    <h2>Panou Administrator</h2>
</div>
<div class="content">
    <!-- notification message -->
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success">
            <h3>
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </h3>
        </div>

    <?php endif ?>

    <!-- logged in admin information -->
    <?php if ($session->exists('username')) : ?>
        <p>Bun venit, <strong><?php echo htmlspecialchars($session->get('username'));?></strong></p>
        <p><a href="admin_panel.php?logout='1'" style="color: red;">Log out</a></p>
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
        <th>Speaker</th>
        <th>Acțiuni</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($event = mysqli_fetch_assoc($events)): ?>
        <tr>
            <td><?php echo htmlspecialchars($event['titlu']); ?></td>
            <td><?php echo htmlspecialchars($event['descriere']); ?></td>
            <td><?php echo $event['data']; ?></td>
            <td><?php echo $event['ora']; ?></td>
            <td><?php echo htmlspecialchars($event['locatie']); ?></td>
            <td><?php
                $speaker_id = $event['speaker_id'];
                $speakerQuery = "SELECT nume FROM speakeri WHERE id = ?";
                $stmt = mysqli_prepare($db, $speakerQuery);
                mysqli_stmt_bind_param($stmt, "i", $speaker_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $speaker_nume);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                echo htmlspecialchars($speaker_nume);
                ?></td>
            <td><a href="edit_event.php?id=<?php echo $event['id']; ?>">Edit</a></td>
            <td><a href="delete_event.php?id=<?php echo $event['id']; ?>">Sterge</a></td>
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

    <label>Mail:</label>
    <input type="email" name="mail" required><br>

    <label>Speaker</label>
    <select name="speaker_id" required>
        <?php
        // Obțineți lista de speakeri din baza de date
        $speakerQuery = "SELECT id, nume FROM speakeri";
        $speakerResult = mysqli_query($db, $speakerQuery);

        // Afiseaza fiecare speaker ca o opțiune în meniul derulant
        while ($speaker = mysqli_fetch_assoc($speakerResult)) {
            echo "<option value='" . $speaker['id'] . "'>" . htmlspecialchars($speaker['nume']) . "</option>";
        }
        ?>
    </select><br>

    <label>Preț Bilet:</label>
    <input type="number" step="0.01" name="pret_bilet" required><br>

    <button class="btn" style="width: 30%" type="submit" name="adauga_eveniment">Adaugă</button>
</form>

<!-- Tabel pentru speakeri -->
<table>
    <h2 class="header">Speakeri</h2>
    <thead>
    <tr>
        <th>Nume</th>
        <th>Descriere</th>
        <th>Acțiuni</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($speaker = mysqli_fetch_assoc($speakeri)): ?>
        <tr>
            <td><?php echo htmlspecialchars($speaker['nume']); ?></td>
            <td><?php echo htmlspecialchars($speaker['descriere']); ?></td>
            <td><a href="edit_speaker.php?id=<?php echo $speaker['id']; ?>">Edit</a></td>
            <td><a href="delete_speaker.php?id=<?php echo $speaker['id']; ?>">Sterge</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Formular pentru adăugarea speakerului -->
<h2 class="header">Adaugă speaker</h2>
<form method="post" action="admin_panel.php">
    <label>Nume:</label>
    <input type="text" name="nume" required><br>
    <label>Descriere:</label>
    <input type="text" name="descriere" required><br>
    <button class="btn" style="width: 30%" type="submit" name="adauga_speaker">Adaugă</button>
</form>

<!-- Tabel pentru parteneri -->
<table>
    <h2 class="header">Parteneri</h2>
    <thead>
    <tr>
        <th>Nume</th>
        <th>Descriere</th>
        <th>Acțiuni</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($partener = mysqli_fetch_assoc($parteneri)): ?>
        <tr>
            <td><?php echo htmlspecialchars($partener['nume']); ?></td>
            <td><?php echo htmlspecialchars($partener['descriere']); ?></td>
            <td><a href="edit_partener.php?id=<?php echo $partener['id']; ?>">Edit</a></td>
            <td><a href="delete_partener.php?id=<?php echo $partener['id']; ?>">Sterge</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Formular pentru adăugarea partenerului -->
<h2 class="header">Adaugă partener</h2>
<form method="post" action="admin_panel.php">

    <label>Nume:</label>
    <input type="text" name="nume" required><br>

    <label>Descriere:</label>
    <input type="text" name="descriere" required><br>

    <button class="btn" style="width: 30%" type="submit" name="adauga_partener">Adaugă</button>
</form>

<!-- Tabel pentru activitati -->
<table>
    <h2 class="header">Activitati</h2>
    <thead>
    <tr>
        <th>Nume</th>
        <th>Descriere</th>
        <th>Ora de Start</th>
        <th>Ora de Final</th>
        <th>Parteneri</th>
        <th>Acțiuni</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($activitate = mysqli_fetch_assoc($activitati)): ?>
        <tr>
            <td><?php echo htmlspecialchars($activitate['titlu_activitate']); ?></td>
            <td><?php echo htmlspecialchars($activitate['descriere']); ?></td>
            <td><?php echo htmlspecialchars($activitate['ora_start']); ?></td>
            <td><?php echo htmlspecialchars($activitate['ora_final']); ?></td>
            <td>
                <?php
                $query_parteneri_activitate = "SELECT p.nume FROM parteneri p
                                    INNER JOIN parteneri_activitati ep ON p.id = ep.partener_id
                                    INNER JOIN agenda a ON ep.eveniment_id = a.eveniment_id
                                    WHERE a.id = " . $activitate['id'];

                $result_parteneri_activitate = mysqli_query($db, $query_parteneri_activitate);

                while ($partener_activitate = mysqli_fetch_assoc($result_parteneri_activitate)) {
                    echo htmlspecialchars($partener_activitate['nume']) . '<br>';
                }
                ?>
            </td>
            <td><a href="edit_activity.php?id=<?php echo $activitate['id']; ?>">Edit</a></td>
            <td><a href="delete_activity.php?id=<?php echo $activitate['id']; ?>">Sterge</a></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Formular pentru adăugarea activității în cadrul unui eveniment existent -->
<h2 class="header">Adaugă Activitate în Eveniment</h2>
<form method="post" action="admin_panel.php">

    <label>Eveniment:</label>
    <select name="eveniment_id" required>
        <?php
        // Obțineți lista de evenimente din baza de date
        $eventQuery = "SELECT id, titlu FROM evenimente";
        $eventResult = mysqli_query($db, $eventQuery);

        // Afiseaza fiecare eveniment ca o opțiune în meniul derulant
        while ($event = mysqli_fetch_assoc($eventResult)) {
            echo "<option value='" . $event['id'] . "'>" . htmlspecialchars($event['titlu']) . "</option>";
        }
        ?>
    </select><br>

    <label>Descriere:
        <textarea name="descriere_activitate" required></textarea><br>
    </label>

    <label>Ora de Start:
        <input type="time" name="ora_start_activitate" required><br>
    </label>

    <label>Ora de Final:
        <input type="time" name="ora_final_activitate" required><br>
    </label>
    <label>Parteneri:</label>
    <select name="parteneri_activitate[]" multiple>
        <?php
        // Obțineți lista de parteneri din baza de date
        $partenerQuery = "SELECT id, nume FROM parteneri";
        $partenerResult = mysqli_query($db, $partenerQuery);

        // Afișează fiecare partener ca o opțiune în meniul derulant
        while ($partener = mysqli_fetch_assoc($partenerResult)) {
            echo "<option value='" . $partener['id'] . "'>" . htmlspecialchars($partener['nume']) . "</option>";
        }
        ?>
    </select><br>
    <button class="btn" style="width: 30%" type="submit" name="adauga_activitate">Adaugă Activitate</button>
</form>


</body>
</html>
