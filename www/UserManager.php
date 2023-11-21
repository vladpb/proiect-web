<?php
include_once 'Database.php';

class UserManager {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function loginUser($username, $password) {
        $response = ['errors' => []];

        if (empty($username)) {
            $response['errors'][] = "Username is required";
        }
        if (empty($password)) {
            $response['errors'][] = "Password is required";
        }

        if (count($response['errors']) == 0) {
            $password = md5($password);
            $query = "SELECT * FROM utilizatori WHERE username='$username' AND parola='$password'";
            $results = $this->db->query($query);

            if ($results && $results->num_rows == 1) {
                $user = $results->fetch_assoc();
                $response['user'] = $user;
            } else {
                $response['errors'][] = "Incorrect username/password combination";
            }
        }

        return $response;
    }
public function registerUser($username, $email, $password) {

$errors = [];
if (empty($username)) { array_push($errors, "Username-ul este obligatoriu"); }
if (empty($email)) { array_push($errors, "Email-ul este obligatoriu"); }
if (empty($password)) { array_push($errors, "Parola este obligatorie"); }

if (count($errors) > 0) {
return ['success' => false, 'errors' => $errors];
}

$encryptedPassword = md5($password);

$this->db->query("INSERT INTO utilizatori (username, email, parola) VALUES ('$username', '$email', '$encryptedPassword')");
return ['success' => true];
}

}
