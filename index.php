<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION["email"];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home Page</title>
</head>

<body>
    <h1>Welcome,
        <?php echo $email; ?>
    </h1>
    <a href="logout.php">Logout</a>
</body>

</html>