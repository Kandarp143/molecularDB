<?php include('include/header.php') ?>
<html>
<head> <?php include('include/links.php') ?>
</head>
<body>

<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <h1 class="title">Add New Molecule</h1>
                <div class="entry">
                    <?php
                    if (!isset($_SESSION['processInsert']) || (isset($_SESSION['processInsert'])
                            && !$_SESSION['processInsert']['success'])
                    ) {
                        include('include/addmst.php');
                    }
                    ?>
                </div>
                <!-- display error-->
                <?php
                if (isset($_SESSION['processInsert'])) {
                    if (!$_SESSION['processInsert']['success']) {
                        echo '<p class="msg-err"> Errors [';
                        foreach ($_SESSION['processInsert']['errors'] as $err) {
                            echo $err . ', ';
                        }
                        echo ']</p>';

                    } else {
                        echo '<br/><br/><h3 class="msg-suc">
                            Molecule Successfully Inserted ! Go to detail : '
                            . '<a href="moldetail.php?id=' . $_SESSION['processInsert']['id'] . '">' . $_SESSION['processInsert']['name'] . '</a></h3>';
                    }
                }
                ?>
                <h1 class="title">Add References</h1>
                <div class="entry">
                    <!--                    manage reference-->
                    <?php
                    if (isset($_SESSION['processInsert'])) {
                        //if errors
                        if (!$_SESSION['processInsert']['success']) {
                            echo '<h4> Please add Molecule First !</h4>';
                        } else {
                            $masterID = $_SESSION['processInsert']['id'];
                            unset($_SESSION['processInsert']);
                            echo '<br/><br/><a class="a-button" href="mapref.php?id=' . $masterID . '">Add Reference</a></p>';
                        }
                    } else {
                        echo '<h4> Please add Molecule First !</h4>';
                    }
                    ?>
                </div>
            </div>
        </div>
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

