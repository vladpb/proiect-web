<?php
include 'Database.php';
include 'SessionManager.php';
include 'UserManager.php';
include 'header.php';  // Assuming header.php does not require changes

$db = new Database();
$session = new SessionManager();
$userManager = new UserManager($db);

// Check if user is already logged in
if ($session->exists('username') && $session->exists('esteAdministrator') && $session->get('esteAdministrator')) {
    header('location: admin_panel.php');
    exit();
}

// Handling the login logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call to UserManager method to handle login
    $loginStatus = $userManager->loginUser($username, $password);

    if ($loginStatus) {
        // Set session variables upon successful login
        $session->set('username', $username);
        $session->set('esteAdministrator', $loginStatus['is_admin']);

        // Redirect to admin panel if user is an admin
        if ($loginStatus['is_admin']) {
            header('location: admin_panel.php');
            exit();
        }

        header('location: index.php');
        exit();

    } else {
        // Handle login errors
        // Assuming errors are returned as an array
        $errors = $loginStatus['errors'];
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="header">
    <h2>Login</h2>
</div>

<form method="post" action="login.php">
    <?php include('errors.php');?>
    <div class="input-group">
        <label>Username</label>
        <input type="text" name="username" >
    </div>
    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password">
    </div>
    <div class="input-group">
        <button type="submit" class="btn" name="login_user">Login</button>
    </div>
    <p>
        Not yet a member? <a href="register.php">Sign up</a>
    </p>
</form>
</body>
</html>