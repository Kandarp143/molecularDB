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

/*
 * if no errors then operate
 * */
$master_id = $_GET['id'];
if (empty($errors)) {
//declare
    $display_id = intval($_POST['displayId']);
    $substance = $_POST['substance'];
    $casno = $_POST['casno'];
    $name = $_POST['name'];
    $modeltype = getModelType($_POST['lj'], $_POST['charge'], $_POST['dipole'], $_POST['quadrupole']);
    $description = $_POST['description'];
    $type = $_POST['type'];
    $disp_sh = isset($_POST['disp_sh']) ? 1 : 0;
    $user_mode = isset($_POST['user_mode']) ? 1 : 0;
    $ls1 = isset($_POST['ls1']) ? 1 : 0;
    $ms2 = isset($_POST['ms2']) ? 1 : 0;
    $lam = isset($_POST['lam']) ? 1 : 0;
    $gro = isset($_POST['gro']) ? 1 : 0;


    try {
        // update data
        $db = new Database();
        $db->update('UPDATE pm_master SET display_id=?,filename = ?,cas_no =?,name = ?,model_type= ?,lj= ?,charge= ?, dipole= ?, quadrupole= ?, description = ?,type=?,disp_sh= ?,user_mode=?,ls1=?,ms2=?,lam=?,gro=? 
WHERE master_id= ?', array($display_id, $substance, $casno, $name, $modeltype, $_POST['lj'], $_POST['charge'], $_POST['dipole'], $_POST['quadrupole'], $description, $type, $disp_sh, $user_mode, $ls1, $ms2, $lam, $gro, $master_id));

        //upload profile
        if (!empty($_FILES['profile']['tmp_name'])) {
            $profileName = 'PM-' . $master_id . '.png';
            uploadFile($_FILES['profile']['tmp_name'], 'img/profile/', $profileName);
        }

        $data['success'] = true;
        $data['message'] = 'Success!';
        $data['id'] = $master_id;
    } catch (Exception $e) {
        $error = $e;
//        echo $e;
    }


} else {
    $data['success'] = false;
    $data['errors'] = $errors;
    $data['id'] = $master_id;

}
//
$_SESSION['processUpdateHead'] = $data;
//

$url = 'updatemol.php?id=' . $master_id;
if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}
