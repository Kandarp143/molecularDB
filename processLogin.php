<?php

session_start(); // Starting Session
$error = ''; // Variable To Store Error Message
require 'database.php';

// check posted by submit button
if (isset($_POST['login-submit'])) {
    // check user-id and password posted
    if (isset($_POST['inputUser']) && isset($_POST['inputPassword'])) {
        //store to var
        $usr = $_POST['inputUser'];
        $pass = $_POST['inputPassword'];
        //db thing
        $pdo = Database::connect();
        $query = "SELECT * FROM usr_access WHERE usr_id = '$usr' and usr_pass = '$pass'";
        $result = $pdo->query($query)->fetchAll();

        if (count($result) == 1) {
            $_SESSION['usr'] = $usr;
            $result2 = $pdo->query($query)->fetch();
            $_SESSION['usr_acc'] = $result2['usr_role'];
            $_SESSION['act'] = 'false';
            if ($_SESSION['usr_acc'] == 'SYS') {
                $_SESSION['act'] = 'true';
            }
            // redirect home page.
            header("location: welcome.php");
            die();
        } else {
            //back to login
            header('location: index.php?invalid=true');
            die();
        }
    } else {
        //back to login
        header('location: index.php?invalid=true');
        die();
    }
}
?>
