<?php
//going to use session var
session_set_cookie_params(0);
session_start();
require_once 'database.php';
require_once 'mailer/PHPMailerAutoload.php';
require_once 'funcation/othFunc.php';
require_once 'funcation/mailFunc.php';
require_once 'funcation/fileFunc.php';
require_once 'config.php';


$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data

$ref_id = $_GET['id'];
/* Validation */
if (!empty($_POST)) {
    if ($_POST['addPass'] != deletePassword || empty($_POST['addPass']))
        $errors['addPassWrong'] = 'Wrong password ! Try again';
} else {
    $errors['post'] = 'Empty form can not submit , Please add additional password ';
}


if (empty($errors)) {
    try {
        $db = new Database();
        $db->beginTransaction();
        $message = referenceMessageKey($ref_id);
        $time = timeStamp();
        $fileName = $db->selectValue('DISTINCT bib_title', 'pm_bib', 'bib_key', $ref_id);
        /*delete records*/
        $db->delete('DELETE FROM pm_bib  WHERE bib_key = ?', array($ref_id));
        /*send mail*/
        sendMail(array(emailDev, emailAdmin), 'Alert ! Reference :' . $fileName . ' has been deleted at (' . $time . ')', $message);

        /*generate log */
        $logFileName = 'ref' . $ref_id . '-log-' . $time . '.txt';
        $myfile = fopen(rootLog . $logFileName, "w") or die("Unable to open file!");
        fwrite($myfile, $message);

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

$_SESSION['processDelRef'] = $data;

$url = 'deleteRef.php?id=' . $ref_id;
if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}
?>