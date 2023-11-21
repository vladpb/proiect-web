<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['esteAdministrator'] != 1) {
    $_SESSION['msg'] = "Trebuie să te loghezi ca admin pentru a vedea această pagină";
    header('location: login.php');
    exit;
}

$db = mysqli_connect('localhost', 'root', '', 'events');

$speaker = array('nume' => '', 'descriere' => '');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM speakeri WHERE id='$id'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $speaker = mysqli_fetch_assoc($result);
    } else {
        header('location: admin_panel.php');
        exit;
    }
}

if (isset($_POST['update_speaker'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $nume = mysqli_real_escape_string($db, $_POST['nume']);
    $descriere = mysqli_real_escape_string($db, $_POST['descriere']);

    $query = "UPDATE speakeri SET nume='$nume', descriere='$descriere' WHERE id='$id'";
    mysqli_query($db, $query);
    $_SESSION['success'] = "Speaker actualizat cu succes!";
    header('location: admin_panel.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editare Speaker</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
    <h2>Editare Speaker</h2>
</div>

<form method="post" action="edit_speaker.php">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="input-group">
        <label>Nume:</label>
        <input type="text" name="nume" value="<?php echo $speaker['nume']; ?>">
    </div>
    <div class="input-group">
        <label>Descriere:</label>
        <textarea name="descriere"><?php echo $speaker['descriere']; ?></textarea>
    </div>
    <div class="input-group">
        <button type="submit" class="btn" name="update_speaker">Salvează modificările</button>
    </div>
</form>

</body>
</html>
