<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION["email"];

$usersData = file('users.txt', FILE_IGNORE_NEW_LINES);
$isManager = false;

foreach ($usersData as $userData) {
    list($username, $storedEmail, $storedPassword, $role) = explode(",", $userData);
    if ($email == $storedEmail && $role === 'manager') {
        $isManager = true;
        break;
    }
}

if (!$isManager) {
    header("Location: index.php"); 
    exit();
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Manager Page</title>
</head>

<body>
    <h2>Hello! this is the manager role page.</h2>
    <a href="logout.php">Logout</a>
</body>

</html>