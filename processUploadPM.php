<?php
//going to use session var
session_set_cookie_params(0);
session_start();
require 'database.php';
require_once 'function/fileFunc.php';

$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data

$master_id = $_GET['id'];

//pm file
if (empty($_FILES['pmfile']['tmp_name'])) {//reqired file
    $errors['pmfile'] = 'PM File not found ! Please upload again';
} else {
    if (getExt($_FILES['pmfile']['name']) !== '.pm')//should be pm
        $errors['pmfileType'] = 'PM File should be .PM file';
}

if (empty($errors)) {
    try {
        $db = new Database();
        $db->beginTransaction();

        /*pm record (detail record)*/
        processMolDetail($master_id, parsePMFile($_FILES['pmfile']['tmp_name']), 'up', $db);

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

$_SESSION['processUploadPM'] = $data;

$url = 'updatemol.php?id=' . $master_id;
if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}

?>