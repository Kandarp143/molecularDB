<?php include('include/header.php') ?>
<?php
require_once 'Vec.php';
require_once 'database.php';
require_once 'funcation/othFunc.php';
require_once 'funcation/fileFunc.php';
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
    $disp_sh = $data[0]['disp_sh'];
    $ms2 = $data[0]['ms2'];
    $ls1 = $data[0]['ls1'];

}
?>
<!-- Design by Kandarp -->
<html>
<head>
    <?php include('include/links.php') ?>

    <script>
        function nextMol() {
            var $_GET = <?php echo json_encode($_GET); ?>;
            var curr_id = $_GET['id'];
            var storedNames = JSON.parse(window.localStorage.getItem("stored_ids"));
//            alert(storedNames);
//            alert(curr_id);
            if (storedNames.indexOf(curr_id) != storedNames.length - 1) {
                var next_id = storedNames[storedNames.indexOf(curr_id) + 1];
                window.location = "moldetail.php?id=" + next_id;
                //            alert(storedNames);
            } else {
                alert('This is the last molecule');
            }
//            alert(storedNames);
//            alert('NEXT' + next_id);

        }

        function prevMol() {
            var $_GET = <?php echo json_encode($_GET); ?>;
            var curr_id = $_GET['id'];
            var storedNames = JSON.parse(window.localStorage.getItem("stored_ids"));
            if (storedNames.indexOf(curr_id) != 0) {
                var prev_id = storedNames[storedNames.indexOf(curr_id) - 1];
                window.location = "moldetail.php?id=" + prev_id;
            } else {
                alert('This is the first molecule');
            }
//            alert(storedNames);
//            alert('PREV' + prev_id);
        }
    </script>
</head>
<body>
<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <!--                molecule header part-->
                <h1 class="title">
                    <?php echo $name; ?>
                    <span style="float: right;">
                    <input type="button" class="paginate_moldetail" onclick="prevMol()" value="<"/>
                    <input type="button" class="paginate_moldetail" onclick="nextMol()" value=">"/>
                    </span>
                </h1>
                <?php include('include/detheader.php') ?>
                <!--                molecule detail part : Rigid -->
                <h1 class="title">Force Field </h1>
                <div class="entry">
                    <p><?php include('include/detmatrix.php') ?></p>
                </div>
                <!--                molecule detail part : Flexible-->
                <div class="entry">
                    <p><?php include('include/detFelxi.php') ?></p>
                </div>
                <!--                molecule Reference part-->
                <h1 class="title">Download Files</h1>
                <div class="entry">
                    <p><?php include('include/detDown.php') ?></p>
                </div>
                <!--                molecule Reference part-->
                <h1 class="title">Reference</h1>
                <div class="entry">
                    <p>
                        <strong>
                            <?php echo referenceMessage($master_id) ?>
                        </strong>
                    </p>
                    <!--if it comes from new reference-->
                    <?php
                    $sParam = 'processInsRef';  /*page name of processor*/
                    $sMsg = 'Reference Successfully Created and Mapped !';
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
                    <?php
                    $sParam = 'processMapRef';  /*page name of processor*/
                    $sMsg = 'Reference Successfully Mapped !';
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
                </div>
            </div>
        </div>
        <?php if ($_SESSION['act'] == 'true') { ?>
            <div id="sidebar">
                <div id="sidebar-content">
                    <div id="sidebar-bgbtm">
                        <ul>
                            <li id="s">
                                <h2>Actions</h2>
                                <p style="text-align: center">
                                    <a class="a-button" href="updatemol.php?id=<?php echo $master_id ?>">Update
                                        Molecule</a>
                                </p>
                            </li>
                            <li>
                                <p style="text-align: center">
                                    <a class="a-button a-danger" href="deletemol.php?id=<?php echo $master_id ?>">Delete
                                        Molecule</a>
                                </p>
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
