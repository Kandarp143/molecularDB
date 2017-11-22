<?php
/*--------- Session Error Handling--------*/

/*--- Process----*/
//going to use session var
session_set_cookie_params(0);
session_start();
require 'database.php';
require_once 'funcation/fileFunc.php';

$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data


/* Validation */

$errors['post'] = 'Error Msg';

if (empty($errors)) {
    try {

        /* todo task*/

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

$url = 'update.php?id=' . $master_id;
if (headers_sent()) {
    die("Redirect failed. Please click on this link: <a href= $url> CLICK HERE <a/>");
} else {
    exit(header('location:' . $url));
}

/*--- View----*/
?>
<!-- Display error -->
<?php
$sParam = 'processDelete';  /*page name of processor*/
$sMsg = 'Molecule deleted successfully !';
if (isset($_SESSION[$sParam])) {
    if (!$_SESSION[$sParam]['success']) {
        echo '<p class="msg-err"> Errors [';
        foreach ($_SESSION[$sParam]['errors'] as $err) {
            echo $err . ', ';
        }
        echo ']</p>';

    } else {
        echo '<br/><br/><h3 class="msg-suc">' . $sMsg . ' </h3>';
    }

    unset($_SESSION[$sParam]);
}
?>

