<?php
//going to use session var
session_set_cookie_params(0);
session_start();
require 'database.php';
require_once 'funcation/fileFunc.php';

$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data

$master_id = $_GET['id'];

if (empty($errors)) {
    try {
        $db = new Database();
        $db->beginTransaction();

        /*pm record (detail record)*/
        processMolDetail($master_id, parsePMArray(explode("\n", $_POST['confirmationText'])), 'up', $db);

        $db->commitTransaction();

        $data['success'] = true;
        $data['message'] = 'Success!';
        $data['id'] = $master_id;
    } catch (Exception $e) {
        $error = $e;
    }

} else {
    $data['success'] = false;
    $data['errors'] = $errors;
    $data['id'] = $master_id;

}

$_SESSION['processOnlinePM'] = $data;

$url = 'updatemol.php?id=' . $master_id;
if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}
?>