<?php include('include/header.php') ?>

<?php
require_once 'database.php';
require_once 'funcation/othFunc.php';
require_once 'funcation/fileFunc.php';
$master_id = 0;
$master_id = $_REQUEST['id'];
$pdo = Database::connect();
$db = new Database();
$data = $db->selectRecords('select * from pm_master where master_id = ?', array($master_id));
if (!empty($data)) {
    $substance = $data[0]['filename'];
    $casno = $data[0]['cas_no'];
    $name = $data[0]['name'];
    $description = $data[0]['description'];
    $type = $data[0]['type'];
    $disp_sh = $data[0]['disp_sh'];
    $dis_id = $data[0]['display_id'];
    $lj = $data[0]['lj'];
    $charge = $data[0]['charge'];
    $dipole = $data[0]['dipole'];
    $quadrupole = $data[0]['quadrupole'];
    $user_mode = $data[0]['user_mode'];
    $ls1 = $data[0]['ls1'];
    $ms2 = $data[0]['ms2'];
    $lam = $data[0]['lam'];
    $gro = $data[0]['gro'];

}
?>
<!-- Design by Kandarp -->
<html>
<head> <?php include('include/links.php') ?>
</head>
<body>

<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <h1 class="title">Update Master Data </h1>
                <div class="entry">
                    <!--        molecule header-->
                    <?php include('include/upheader.php') ?>
                </div>
            </div>
            <div class="post">
                <h1 class="title">Update Force Fields
                </h1>

                <div class="entry">
                    <!--        molecule file upload-->
                    <?php include('include/updet.php') ?>
                </div>
                <h1 class="title">Reference</h1>
                <div class="entry">
                    <p>
                        <strong>
                            <?php echo referenceMessage($master_id) ?>
                        </strong>
                    </p>
                    <a href="mapref.php?id=<?php echo $master_id ?>" class="a-button">Update Refernece</a>
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
                                    <a class="a-button" href="moldetail.php?id=<?php echo $master_id ?>">
                                        Back to Molecule Detail</a>
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end #sidebar -->
        <?php } ?>
        <!-- end #content -->
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
