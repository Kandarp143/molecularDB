<?php
//going to use session var
session_set_cookie_params(0);
session_start();
require 'database.php';
require_once 'funcation/fileFunc.php';

$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data

$master_id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = 0;

/* Validation */
//inputdata
if (!empty($_POST)) {
    if (empty($_POST['bib_key']))
        $errors['bib_key'] = 'No reference selected ! Select at least one Reference.';
} else {
    $errors['post'] = 'Empty form can not submit , Please add reference detail';
}

if (empty($errors)) {
    try {
        $db = new Database();
        $db->beginTransaction();
        //1.get reference key
        $id = $_POST['bib_key'];
        //2.select title
        $title = $db->selectValue('bib_title', 'pm_bib', 'bib_key', $id);

        //if have to map with some mol
        $db->update('UPDATE pm_master SET bibtex_ref_key = ?, bibtex_key = ? WHERE master_id =?',
            array($id, trim($title, ' '), $master_id));

        $db->commitTransaction();
        $data['success'] = true;
        $data['message'] = 'Success!';
        $data['id'] = $id;
    } catch (Exception $e) {
        $error = $e;
    }
} else {
    $data['success'] = false;
    $data['errors'] = $errors;
    $data['id'] = $id;

}

$_SESSION['processMapRef'] = $data;
$url = 'mapRef.php?id=' . $master_id;

//if map done and insert done
if ($master_id != 0 && $data['success'] == true) {
    $url = 'moldetail.php?id=' . $master_id;
}

if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}