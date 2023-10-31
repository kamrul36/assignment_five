<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $usersData = file('users.txt', FILE_IGNORE_NEW_LINES);

    foreach ($usersData as $userData) {
        list($username, $storedEmail, $storedPassword, $role) = explode(",", $userData);
        if ($email == $storedEmail && password_verify($password, $storedPassword)) {
            session_start();
            $_SESSION["email"] = $email;

            if ($role === 'admin') {
                header("Location: role_management.php");
            } else if ($role === "manager") {
                header("Location: manager_page.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }
    }

    header("Location: login.php?error=1");
}
?>