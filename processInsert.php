<?php
//going to use session var
session_set_cookie_params(0);
session_start();
require 'database.php';
require_once 'funcation/fileFunc.php';
require_once 'funcation/othFunc.php';

$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data
/* Validation */
//inputdata
if (!empty($_POST)) {
    if (empty($_POST['substance']))
        $errors['substance'] = 'Substance required field';
    if (empty($_POST['casno']))
        $errors['casno'] = 'casno required field';
    if (empty($_POST['name']))
        $errors['name'] = 'name required field';
    if ($_POST['lj'] . $_POST['charge'] . $_POST['dipole'] . $_POST['quadrupole'] == '0000')
        $errors['modeltype'] = 'invalid modeltype';
    if (intval($_POST['displayId']) == 0)
        $errors['displayId'] = 'Display Id required not zero integer value';
} else {
    $errors['post'] = 'Empty form can not submit , Please add molecule detail';
}

//profile image
$allowedSize = 5000000;
$allowedFtypes = array('.jpg', '.gif', '.bmp', '.png');
if (!empty($_FILES['profile']['tmp_name'])) {
    if (!in_array(getExt($_FILES['profile']['name']), $allowedFtypes))//should be image
        $errors['profileType'] = 'Profile image  should be image file : .jpg,.gif,.bmp, .png ';
    if (!validateSize($_FILES['profile']['size'], $allowedSize))//less then 5 MB
        $errors['profileSize'] = 'File should be less then 5 MB';
}
//pm file
if (empty($_FILES['pmfile']['tmp_name'])) {//reqired file
    $errors['pmfile'] = 'PM File not found ! Please upload again';

} else {
    if (getExt($_FILES['pmfile']['name']) !== '.pm')//should be pm
        $errors['pmfileType'] = 'PM File should be .PM file';
    if (!validateSize($_FILES['profile']['size'], $allowedSize))//less then 5 MB
        $errors['pmfileSize'] = 'PM File should be less then 5 MB';

    //validate selected type and uploaded pm
    $devider = array_search("NIdfTypes", array_keys(parsePMFile($_FILES['pmfile']['tmp_name'])));
    if (empty(!$devider) && $_POST['type'] != 'Flexible') {
        $errors['typeMisMatch'] = 'Selected type is : Rigid & Uploaded pm file is : Flexible';
    }
    if (empty($devider) && $_POST['type'] != 'Rigid') {
        $errors['typeMisMatch'] = 'Selected type is : Flexible & Uploaded pm file is : Rigid';
    }

}

/*
 * if no errors then operate
 * */
$masterid = 0;
if (empty($errors)) {
    //declare
    $substance = $_POST['substance'];
    $dis_id = intval($_POST['displayId']);
    $casno = $_POST['casno'];
    $name = $_POST['name'];
    $modeltype = getModelType($_POST['lj'], $_POST['charge'], $_POST['dipole'], $_POST['quadrupole']);
    $description = $_POST['description'];
    $type = $_POST['type'];
    $profileName = '';
    $disp_sh = isset($_POST['disp_sh']) ? 1 : 0;
    $user_mode = isset($_POST['user_mode']) ? 1 : 0;

    // insert data
    try {
        $db = new Database();
        $db->beginTransaction();
        /* master record*/
        $masterid = $db->insert('INSERT INTO pm_master (display_id,filename,cas_no,name,bibtex_ref_key,bibtex_key,bibtex_year,model_type,lj,charge,dipole,quadrupole,memory_loc,description,type,disp_sh,user_mode) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
            array($dis_id, $substance, $casno, $name, 0, '-', 0, $modeltype, $_POST['lj'], $_POST['charge'], $_POST['dipole'], $_POST['quadrupole'], 'img/profile/' . $profileName, $description, $type, $disp_sh, $user_mode));

        /*pm record (detail record)*/
        processMolDetail($masterid, parsePMFile($_FILES['pmfile']['tmp_name']), 'ins', $db);

        $db->commitTransaction();

        //upload profile
        if (!empty($_FILES['profile']['tmp_name'])) {
            $profileName = 'PM-' . $masterid . '.png';
            uploadFile($_FILES['profile']['tmp_name'], 'img/profile/', $profileName);
        }

        $data['success'] = true;
        $data['message'] = 'Success!';
        $data['id'] = $masterid;
        $data['name'] = $substance;

    } catch
    (Exception $e) {

        $error = $e;
//        echo $e;
    }


} else {
    $data['success'] = false;
    $data['errors'] = $errors;
    $data['id'] = 0;

}

$_SESSION['processInsert'] = $data;
$url = 'addmol.php';
if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}

?>