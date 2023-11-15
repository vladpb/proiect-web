<?php

session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'events');
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// REGISTER USER
if (isset($_POST['reg_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    if (empty($username)) { array_push($errors, "Username-ul este obligatoriu"); }
    if (empty($email)) { array_push($errors, "Email-ul este obligatoriu"); }
    if (empty($password_1)) { array_push($errors, "Parola este obligatorie"); }
    if ($password_1 != $password_2) {
        array_push($errors, "Parolele nu se potrivesc");
    }

    if (count($errors) == 0) {
        $password = md5($password_1); // encrypt the password before saving in the database
        $query = "INSERT INTO utilizatori (username, email, parola) VALUES('$username', '$email', '$password')";
        if (mysqli_query($db, $query)) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "Esti logat";
            header('location: index.php');
        } else {
            printf("Error: %s\n", mysqli_error($db));
        }
    }
}
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM utilizatori WHERE username='$username' AND parola='$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $user = mysqli_fetch_assoc($results); // obținem informațiile despre utilizator
            $_SESSION['username'] = $username;
            $_SESSION['esteAdministrator'] = $user['esteAdministrator'];

            if ($user['esteAdministrator'] == 1) {
                $_SESSION['success'] = "Esti logat ca administrator";
                header('location: admin_panel.php');
            } else {
                $_SESSION['success'] = "You are now logged in";
                header('location: index.php');
            }
        } else {
            array_push($errors, "Combinatie username/parola gresita");
        }
    }
}

if (isset($_POST['adauga_eveniment'])) {
    // preia datele
    $titlu = mysqli_real_escape_string($db, $_POST['titlu']);
    $descriere = mysqli_real_escape_string($db, $_POST['descriere']);
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $locatie = mysqli_real_escape_string($db, $_POST['locatie']);

    // Obține ID-ul de utilizator
    $queryUser = "SELECT id FROM utilizatori WHERE username='" . $_SESSION['username'] . "'";
    $resultUser = mysqli_query($db, $queryUser);
    if ($resultUser) {
        $userRow = mysqli_fetch_assoc($resultUser);
        $userID = $userRow['id'];
    } else {
        // Handle error
        die("Error fetching user ID: " . mysqli_error($db));
    }

    // introducere în baza de date
    $query = "INSERT INTO evenimente (titlu, descriere, data, ora, locatie, creat_de) VALUES ('$titlu', '$descriere', '$data', '$ora', '$locatie', '$userID')";
    if(!mysqli_query($db, $query)){
        die("Error inserting event: " . mysqli_error($db));
    }
}

?>