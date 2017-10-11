<?php
//going to use session var
session_set_cookie_params(0);
session_start();
require 'database.php';
require_once 'funcation/fileFunc.php';

$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data

$ref_id = isset($_GET['id']) ? $_GET['id'] : 0;

/* Validation */
//inputdata
if (!empty($_POST)) {
    if (empty($_POST['bib_title']))
        $errors['bib_title'] = 'Shorthand Title required field';
    if (empty($_POST['Title']))
        $errors['Title'] = 'Title of Publication required field';
    if (empty($_POST['Author']))
        $errors['Author'] = 'Author required field';
    if (empty($_POST['Journal']))
        $errors['Journal'] = 'Journal required field';
    if (empty($_POST['Publisher']))
        $errors['Publisher'] = 'Publisher required field';
    if (empty($_POST['Volume']))
        $errors['Volume'] = 'Volume required field';
    if (empty($_POST['Number']))
        $errors['Number'] = 'Number required field';
    if (empty($_POST['Pages']))
        $errors['Pages'] = 'Pages required field';
    if (empty($_POST['Year']))
        $errors['Year'] = 'Year required field';
    if (empty($_POST['Doi']))
        $errors['Doi'] = 'DOI required field';
    if (empty($_POST['Url']))
        $errors['Url'] = 'Url required field';
} else {
    $errors['post'] = 'Empty form can not submit , Please add reference detail';
}

if (empty($errors)) {
    try {
        $db = new Database();
        $db->beginTransaction();

        //1.delete old records
        $db->delete('DELETE FROM pm_bib  WHERE bib_key = ?', array($ref_id));

        //2.add new record
        foreach ($_POST as $key => $val) {
            $db->insert('INSERT INTO pm_bib (bib_key,bib_type,bib_title,param,value) values(?, ?, ?,?,?)',
                array($ref_id, '', trim($_POST['bib_title'], ' '), trim($key, ' '), trim($val, ' ')));
        }
        $db->commitTransaction();


        $data['success'] = true;
        $data['message'] = 'Success!';
        $data['id'] = $ref_id;
    } catch (Exception $e) {
        $error = $e;
    }
} else {
    $data['success'] = false;
    $data['errors'] = $errors;
    $data['id'] = $ref_id;

}

$_SESSION['processUpRef'] = $data;
$url = 'updateRef.php?id=' . $ref_id;

if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}