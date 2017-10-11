<?php include('include/header.php');
//to clear previous session
session_unset();
session_destroy();
?>

<!-- Design by Kandarp -->
<html>
<head>
    <?php include('include/links.php') ?>
</head>
<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="header-pic"></div>
        <div id="content">
            <?php include('include/home.php') ?>
        </div>
        <!-- end #content -->
        <?php
        $error = '';
        if (isset($_GET['invalid'])) {
            $error = $_GET['invalid'];
        } ?>
        <div id="sidebar">
            <div id="sidebar-content">
                <div id="sidebar-bgbtm">
                    <ul>
                        <li id="search">
                            <?php include('include/login.php') ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end #sidebar -->
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
