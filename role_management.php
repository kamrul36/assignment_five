<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION["email"];

$usersData = file('users.txt', FILE_IGNORE_NEW_LINES);
$isAdmin = false;

foreach ($usersData as $userData) {
    list($username, $storedEmail, $storedPassword, $role) = explode(",", $userData);
    if ($email == $storedEmail && $role === 'admin') {
        $isAdmin = true;
        break;
    }
}

if (!$isAdmin) {
    header("Location: index.php"); 
    exit();
}


if ($isAdmin) {

    if (isset($_POST["createRole"]) && isset($_POST["newRole"])) {
        $newRole = $_POST["newRole"];
        $roleExists = false;
        foreach ($usersData as $userData) {
            list($username, $storedEmail, $storedPassword, $role) = explode(",", $userData);
            if ($role === $newRole) {
                $roleExists = true;
                break;
            }
        }
        if (!$roleExists) {
   
            $newRoleData = ",$newRole";
            file_put_contents('users.txt', $newRoleData, FILE_APPEND | LOCK_EX);
            header("Location: role_management.php");
        } else {
            echo "Role already exists.";
        }
    }

    // Edit Role
    if (isset($_POST["editRole"]) && isset($_POST["editRoleName"]) && isset($_POST["editedRole"])) {
        $editRoleName = $_POST["editRoleName"];
        $editedRole = $_POST["editedRole"];

        foreach ($usersData as $key => $userData) {
            list($username, $storedEmail, $storedPassword, $role) = explode(",", $userData);
            if ($role === $editRoleName) {
                $usersData[$key] = str_replace($editRoleName, $editedRole, $userData);
            }
        }
  
        file_put_contents('users.txt', implode("\n", $usersData));
        header("Location: role_management.php");
    }


    if (isset($_POST["deleteRole"]) && isset($_POST["deleteRoleName"])) {
        $deleteRoleName = $_POST["deleteRoleName"];

        foreach ($usersData as $key => $userData) {
            list($username, $storedEmail, $storedPassword, $role) = explode(",", $userData);
            if ($role === $deleteRoleName) {
                unset($usersData[$key]);
            }
        }

        file_put_contents('users.txt', implode("\n", $usersData));
        header("Location: role_management.php");
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Role Management</title>
</head>

<body>
    <h1>Admin Role Management Page</h1>

    <h2>Create User Role</h2>
    <form method="post" action="role_management.php">
        Role Name: <input type="text" name="newRole" required>
        <input type="submit" name="createRole" value="Create Role">
    </form>

    <h2>Edit User Role</h2>
    <form method="post" action="role_management.php">
        Existing Role:
        <select name="editRoleName">
            <option value="">Select Role</option>
            <?php
            foreach ($usersData as $userData) {
                list($username, $storedEmail, $storedPassword, $role) = explode(",", $userData);
                if ($role !== 'admin') {
                    echo "<option value=\"$role\">$role</option>";
                }
            }
            ?>
        </select>
        New Role Name: <input type="text" name="editedRole" required>
        <input type="submit" name="editRole" value="Edit Role">
    </form>

    <h2>Delete User Role</h2>
    <form method="post" action="role_management.php">
        Role to Delete:
        <select name="deleteRoleName">
            <option value="">Select Role</option>
            <?php
            foreach ($usersData as $userData) {
                list($username, $storedEmail, $storedPassword, $role) = explode(",", $userData);
                if ($role !== 'admin') {
                    echo "<option value=\"$role\">$role</option>";
                }
            }
            ?>
        </select>
        <input type="submit" name="deleteRole" value="Delete Role">
    </form>

    <a href="logout.php">Logout</a>
</body>

</html>