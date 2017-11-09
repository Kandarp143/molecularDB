<?php


//email configuration
const  mailHost = 'smtp.gmail.com';
const  mailPort = 587;
const  mailUsername = "er.ikndp@gmail.com";
const  mailPassword = "godwithmeking";
const  mailAdmin = "er.ikndp@gmail.com";
const  mailFromName = "Admin@MoleculeDB";
const  mailFromAddress = "Admin@MoleculeDB";
const  mailAltBody = "Plain ! No Content, Contact Admin";


function sendMail($emails, $subject, $message)
{
    date_default_timezone_set('Etc/UTC');
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = false;
    $mail->do_debug = 0;
    $mail->Host = mailHost;
    $mail->Port = mailPort;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = mailUsername;
    $mail->Password = mailPassword;
    $mail->setFrom(mailFromAddress, mailFromName);
    if ($emails > 0) {
        foreach ($emails as $email) {
            $mail->addAddress($email);
        }
    } else {
        $mail->addAddress(mailAdmin);
    }
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = mailUsername;
    try {
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function genMessageMol($masterId)
{
    $db = new Database();
    $master = $db->selectRecords('SELECT DISTINCT * FROM pm_master WHERE master_id =?', array($masterId));
    $detail = $db->selectRecords('SELECT * FROM pm_detail WHERE master_id =?', array($masterId));
    $sitetypes = $db->selectRecords('SELECT COUNT(b.site) nsite,b.site_type 
          FROM ( SELECT DISTINCT a.site_type,a.site FROM pm_detail a WHERE a.master_id=?) b 
          GROUP BY b.site_type', array($masterId));

    //string html
    $message = "<html><body>";
    //makeing master body
    $message .= "<p><b>Following molecule has been deleted from database.</b></p>";
    /* Prepare Header */
    $message .= "<p><b>Molecule Header </b></p>
    <table>";
    foreach ($master as $row) {
        $message .= "<tr style='text-align: left'><th>Substance</th><td>" . toSubstanceTitle($row['filename']) . "</td></tr>";
        $message .= "<tr style='text-align: left'><th>CAS-No</th><td>" . $row['cas_no'] . "</td></tr>";
        $message .= "<tr style='text-align: left'><th>Reference</th><td>[" . $row['bibtex_key'] . "]</td></tr>";
        $message .= "<tr style='text-align: left'><th>Model Type</th><td>" . $row['model_type'] . "</td></tr>";
        $message .= "<tr style='text-align: left'><th>Type</th><td>" . $row['type'] . "</td></tr>";
        $message .= "<tr style='text-align: left'><th>Description</th><td>" . $row['description'] . "</td></tr>";
    }
    $message .= "</table>";

    /* Prepare Detail   */
    $sitetype = null;
    $NSite = null;
    $site = null;
    $cout = 0;
    foreach ($sitetypes as $row) {
        $NSite[$row['site_type']] = $row['nsite'];
    }

    $message .= "<p><b>Molecule Detail</b> </p>";
    foreach ($detail as $row) {
        if ($sitetype != $row['site_type']) {
            $message .= "SiteType" . "&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;" . $row['site_type'] . "<br/>";
            $message .= "NSites" . "&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;" . $NSite[$row['site_type']] . "<br/><br/>";
            $sitetype = $row['site_type'];
            $cout += 1;
        }
        if ($site != $row['site']) {
            $message .= "<br/># " . $row['site'] . "<br/>";
            $site = $row['site'];
        }

        $message .= $row['param'] . "&nbsp;&nbsp;&nbsp;&nbsp; =&nbsp;&nbsp;&nbsp;&nbsp;  " . $row['val'] . "<br/>";
    }

    $message .= "<p><b>Reference</b> </p>";
    $message .= referenceMessage($masterId);


    $message .= "</html></body>";

    return $message;

}


?>