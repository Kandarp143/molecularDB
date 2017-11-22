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
        //1.get last id from db and incr
        $result = $db->selectRecords('SELECT MAX(bib_key)+1 FROM pm_bib', null);
        $id = $result[0][0];
        //2.insert records
        foreach ($_POST as $key => $val) {
            $db->insert('INSERT INTO pm_bib (bib_key,bib_type,bib_title,param,value) values(?, ?, ?,?,?)',
                array($id, '', trim($_POST['bib_title'], ' '), trim($key, ' '), trim($val, ' ')));
        }

        //if have to map with some mol
        if ($master_id != 0) {
            $db->update('UPDATE pm_master SET bibtex_ref_key = ?, bibtex_key = ? WHERE master_id =?',
                array($id, trim($_POST['bib_title'], ' '), $master_id));
        }

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

$_SESSION['processInsRef'] = $data;
$url = 'addRef.php';

//if map done and insert done
if ($master_id != 0 && $data['success'] == true) {
    $url = 'moldetail.php?id=' . $master_id;
}

//if map there but error
if ($master_id != 0 && $data['success'] == false) {
    $url = 'addRef.php?id=' . $master_id;
}

if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}