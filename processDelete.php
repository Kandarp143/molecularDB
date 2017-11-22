<?php
//going to use session var
session_set_cookie_params(0);
session_start();
require_once 'database.php';
require_once 'mailer/PHPMailerAutoload.php';
require_once 'funcation/mailFunc.php';
require_once 'funcation/othFunc.php';
require_once 'funcation/fileFunc.php';
require_once 'config.php';


$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data

$master_id = $_GET['id'];
/* Validation */
if (!empty($_POST)) {
    if ($_POST['addPass'] != deletePassword || empty($_POST['addPass']))
        $errors['addPassWrong'] = ' Wrong password ! Try again';
} else {
    $errors['post'] = 'Empty form can not submit , Please add additional password ';
}


if (empty($errors)) {
    try {

        $db = new Database();
        $message = genMessageMol($master_id);
        $time = timeStamp();

        $db->beginTransaction();

        $fileName = $db->selectValue('filename', 'pm_master', 'master_id', $master_id);

        /*delete records*/
        $db->delete('DELETE FROM pm_master  WHERE master_id = ?', array($master_id));
        $db->delete('DELETE FROM pm_detail  WHERE master_id = ?', array($master_id));
        $db->delete('DELETE FROM pm_flexible  WHERE master_id = ?', array($master_id));

        /*generate log */
        $logFileName = 'mol' . $master_id . '-log' . '.txt';
        $myfile = fopen(rootLog . $logFileName, "w") or die("Unable to open file!");
        //  $myfile = fopen("gen/log/test.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $message);


        /*send mail*/
        sendMail(array(emailDev, emailAdmin), 'Alert ! Molecule :' . $fileName . ' has been deleted at (' . $time . ')', $message);

        $db->commitTransaction();


        /*getting last master id */
        $autoIncr = $db->selectRecords('SELECT max(master_id) FROM pm_master', NULL);

        /*set auto incr -  prepare is not working for alter as per pdo docs*/
        $db->update('ALTER TABLE pm_master AUTO_INCREMENT=' . $autoIncr[0][0], null);


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

$_SESSION['processDelete'] = $data;

$url = 'deletemol.php?id=' . $master_id;
if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}

?>