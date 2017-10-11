<?php include('include/header.php') ?>
<?php
require_once 'database.php';
require_once 'funcation/othFunc.php';
$ref_id = 0;
$ref_id = $_REQUEST['id'];


$sParam = 'processDelRef';  /*page name of processor*/
$sMsg = 'Reference deleted successfully !';
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
                <h1 class="title">Delete Reference</h1>
                <div class="entry">
                    <?php if (isset($_SESSION[$sParam]) && !$_SESSION[$sParam]['success'] || !isset($_SESSION[$sParam])) { ?>
                        <p>Are you sure to delete following reference ?</p>
                        <p style="color: #2b2b2b"><b><?php echo referenceMessageKey($ref_id) ?></b></p>
                        <!--                form-->
                        <form class="form-horizontal" action="processDelRef.php?id=<?php echo $ref_id ?>" method="post">
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
        </div>
        <?php if ($_SESSION['act'] == 'true') { ?>
            <div id="sidebar">
                <div id="sidebar-content">
                    <div id="sidebar-bgbtm">
                        <ul>
                            <li id="s">
                                <h2>Actions</h2>
                                <p style="text-align: center">
                                    <a class="a-button" href="reflist.php">
                                        Back to Reference List</a>
                                </p>
                                <?php unset($_SESSION[$sParam]); ?>
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
