<?php

session_start();

// initializing variables
$username = "";
$email = "";
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

    if (empty($username)) {
        array_push($errors, "Username-ul este obligatoriu");
    }
    if (empty($email)) {
        array_push($errors, "Email-ul este obligatoriu");
    }
    if (empty($password_1)) {
        array_push($errors, "Parola este obligatorie");
    }
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
    $mail = mysqli_real_escape_string($db, $_POST['mail']);
    $pret_bilet = mysqli_real_escape_string($db, $_POST['pret_bilet']);
    $eveniment_id = mysqli_insert_id($db); // ID of the recently added event
    $parteneri_eveniment = isset($_POST['parteneri']) ? $_POST['parteneri'] : array();

    foreach ($parteneri_eveniment as $partener_id) {
        $query_insert_partener_eveniment = "INSERT INTO parteneri_activitati (eveniment_id, partener_id) 
                                              VALUES ('$eveniment_id', '$partener_id')";
        mysqli_query($db, $query_insert_partener_eveniment);
    }

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
    $query = "INSERT INTO evenimente (titlu, descriere, data, ora, locatie, mail, creat_de, pret_bilet) VALUES ('$titlu', '$descriere', '$data', '$ora', '$locatie','$mail', '$userID', '$pret_bilet')";
    if (!mysqli_query($db, $query)) {
        die("Error inserting event: " . mysqli_error($db));
    }
}
if (isset($_POST['adauga_speaker'])) {
    $nume = mysqli_real_escape_string($db, $_POST['nume']);
    $descriere = mysqli_real_escape_string($db, $_POST['descriere']);

    $query = "INSERT INTO speakeri (nume, descriere) VALUES ('$nume', '$descriere')";
    if (!mysqli_query($db, $query)) {
        die("Error inserting speaker: " . mysqli_error($db));
    }
    $_SESSION['success'] = "Speaker adăugat cu succes!";
    header('location: admin_panel.php');
    exit;
}

if (isset($_POST['adauga_partener'])) {
    $nume = mysqli_real_escape_string($db, $_POST['nume']);
    $descriere = mysqli_real_escape_string($db, $_POST['descriere']);

    $query = "INSERT INTO parteneri (nume,descriere) VALUES ('$nume','$descriere')";
    if (!mysqli_query($db, $query)) {
        die("Error inserting partner: " . mysqli_error($db));
    }
    $_SESSION['success'] = "Partener adaugat cu succes!";
    header('location: admin_panel.php');
    exit;
}

if (isset($_POST['adauga_activitate'])) {
    $eveniment_id = mysqli_real_escape_string($db, $_POST['eveniment_id']);
    $descriere_activitate = mysqli_real_escape_string($db, $_POST['descriere_activitate']);
    $ora_start_activitate = mysqli_real_escape_string($db, $_POST['ora_start_activitate']);
    $ora_final_activitate = mysqli_real_escape_string($db, $_POST['ora_final_activitate']);
    $parteneri_activitate = isset($_POST['parteneri_activitate']) ? $_POST['parteneri_activitate'] : array();

    // Verifică dacă evenimentul există
    $query_eveniment = "SELECT titlu FROM evenimente WHERE id = $eveniment_id";
    $result_eveniment = mysqli_query($db, $query_eveniment);
    $eveniment_info = mysqli_fetch_assoc($result_eveniment);

    if ($eveniment_info) {
        $titlu_eveniment = $eveniment_info['titlu'];

        // Inserează activitatea în baza de date
        $query_insert_activitate = "INSERT INTO agenda (titlu_activitate, descriere, ora_start, ora_final, eveniment_id) 
                                    VALUES ('$titlu_eveniment', '$descriere_activitate', '$ora_start_activitate', '$ora_final_activitate', $eveniment_id)";
        mysqli_query($db, $query_insert_activitate);
        $activitate_id = mysqli_insert_id($db); // ID-ul activității recent adăugate

        // Adaugă partenerii asociați activității în tabela parteneri_activitati
        foreach ($parteneri_activitate as $partener_id) {
            $query_insert_partener_activitate = "INSERT INTO parteneri_activitati (partener_id, activitate_id) 
                                                  VALUES ('$partener_id', '$activitate_id')";
            mysqli_query($db, $query_insert_partener_activitate);
        }

        $_SESSION['success'] = "Activitate adăugată cu succes la evenimentul \"$titlu_eveniment\"";
        header('location: admin_panel.php');
        exit();
    } else {
        array_push($errors, "Evenimentul selectat nu există!");
    }
}


if (isset($_POST['adauga_partener_la_activitate'])) {
    $activitate_id = mysqli_real_escape_string($db, $_POST['activitate_id']);
    $parteneri_selectati = isset($_POST['parteneri_activitate']) ? $_POST['parteneri_activitate'] : array();

    foreach ($parteneri_selectati as $partener_id) {
        // Verifică dacă asocierea deja există
        $query_check_association = "SELECT * FROM parteneri_activitati WHERE activitate_id = $activitate_id AND partener_id = $partener_id";
        $result_check_association = mysqli_query($db, $query_check_association);

        if (mysqli_num_rows($result_check_association) == 0) {
            // Introducere în baza de date
            $query_insert_partener_activitate = "INSERT INTO parteneri_activitati (activitate_id, partener_id) VALUES ('$activitate_id', '$partener_id')";
            if (!mysqli_query($db, $query_insert_partener_activitate)) {
                die("Error inserting partner to activity: " . mysqli_error($db));
            }
        }
    }

    $_SESSION['success'] = "Parteneri adăugați cu succes la activitate!";
    header('location: admin_panel.php');
    exit;
}
?>