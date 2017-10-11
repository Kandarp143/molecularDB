<style type="text/css">
    #Div2 {
        display: none;
    }
</style>
<script type="text/javascript">;
    function switchVisible() {
        if (document.getElementById('Div1')) {

            if (document.getElementById('Div1').style.display == 'none') {
                document.getElementById('Div1').style.display = 'block';
                document.getElementById('Div2').style.display = 'none';
            }
            else {
                document.getElementById('Div1').style.display = 'none';
                document.getElementById('Div2').style.display = 'block';
            }
        }
    }
</script>

<!--online edit-->
<div id="Div1">
    <span style="float: right"> Switch to  : <a href="#" class="a-button"
                                                onclick="switchVisible();">Upload updated PM file</a></span>
    <h3 style="color: #2b2b2b;margin-top: 2%"><b>Edit Online</b></h3>


    <form action="processOnlinePM.php?update=true&id=<?php echo $master_id ?>" method="post">
                <textarea id="confirmationText" class="text" cols="86" rows="20"
                          name="confirmationText"><?php include('updetonline.php') ?>
                </textarea>
        <input class="btn btn-success" type="submit" value="Update Force Fields">
    </form>
</div>


<!--update file-->
<div id="Div2">
    <span style="float: right"> Switch to  : <a href="#" class="a-button"
                                                onclick="switchVisible();">Edit online</a></span>
    <h3 style="color: #2b2b2b;margin-top: 2%"><b>Upload Updated PM File</b>
    </h3> <a class="a-button"
             href="include/generateFile.php?id=<?php echo $master_id ?>">Download old file</a>


    <form action="processUploadPM.php?id=<?php echo $master_id ?>" method="post"
          enctype="multipart/form-data">
        <input type="file" name="pmfile" id="pmfile">
        <input class="btn btn-success" type="submit" value="Update Force Fields">
    </form>
</div>
<!-- Display error -->
<?php
//online

$sParam = 'processOnlinePM';  /*page name of processor*/
$sMsg = 'Force fields updated successfully !';
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

//file upload
$sParam = 'processUploadPM';  /*page name of processor*/
$sMsg = 'Force fields updated successfully !';
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