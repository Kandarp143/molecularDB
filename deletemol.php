<?php include('include/header.php') ?>
<?php
require_once 'database.php';
require_once 'funcation/othFunc.php';
$master_id = 0;
$master_id = $_REQUEST['id'];
$db = new Database();
$data = $db->selectRecords('select * from pm_master where master_id = ?', array($master_id));
if (!empty($data)) {
    $substance = $data[0]['filename'];
    $casno = $data[0]['cas_no'];
    $name = $data[0]['name'];
    $modeltype = $data[0]['model_type'];
    $description = $data[0]['description'];
    $type = $data[0]['type'];
    $ref = $data[0]['bibtex_key'];

}
$sParam = 'processDelete';  /*page name of processor*/
$sMsg = 'Molecule deleted successfully !';
?>
<!-- Design by Kandarp -->
<html>
<head>
    <?php include('include/links.php') ?>
</head>
<body>
<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <!--                molecule header part-->
                <h1 class="title">Delete Molecule</h1>
                <?php if (isset($_SESSION[$sParam]) && !$_SESSION[$sParam]['success'] || !isset($_SESSION[$sParam])) { ?>
                    <p>Are you sure to delete following molecule ?</p>
                    <h3 style="color: #2b2b2b"><b>Molecule : <?php echo $name; ?></b></h3>
                    <?php include('include/detheader.php') ?>
                    <!--                form-->
                    <form class="form-horizontal" action="processDelete.php?id=<?php echo $master_id ?>" method="post">
                        <input type="password" placeholder="Enter additional password" name='addPass'
                               class="datagrid"/>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-danger">Yes</button>
                        </div>
                    </form>
                <?php } ?>
                <!-- Display error -->
                <?php
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

                }
                ?>

            </div>
        </div>
        <?php if ($_SESSION['act'] == 'true') { ?>
            <div id="sidebar">
                <div id="sidebar-content">
                    <div id="sidebar-bgbtm">
                        <ul>
                            <li id="s">
                                <h2>Actions</h2>
                                <?php if (isset($_SESSION[$sParam]) && !$_SESSION[$sParam]['success'] || !isset($_SESSION[$sParam])) { ?>
                                    <p style="text-align: center">
                                        <a class="a-button" href="moldetail.php?id=<?php echo $master_id ?>">Back to
                                            Molecule Detail</a>
                                    </p>
                                <?php } else { ?>
                                    <p style="text-align: center">
                                        <a class="a-button" href="mollist.php">Back to Molecule List</a>
                                    </p>
                                <?php }
                                unset($_SESSION[$sParam]); ?>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <!-- end #sidebar -->
        <?php } ?>
        <div style="clear:both; margin:0;"></div>
    </div>
    <!-- end #page -->
</div>
<div id="footer">
    <?php include('include/footer.php') ?>
</div>
<!-- end #footer -->
</body>
</html>
