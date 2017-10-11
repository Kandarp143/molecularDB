<?php include('include/header.php');
require_once('database.php');
require_once 'funcation/othFunc.php';
$master_id = 0;
$master_id = $_GET['id'];
?>
<!-- Design by Kandarp -->
<html>
<head> <?php include('include/links.php') ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"
          media="screen"/>
    <!--javascript-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable({});
        });
    </script>
</head>
<body>

<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <h1 class="title">Available References</h1>
                <div class="entry">

                    <form method="post" action="processMapRef.php?id=<?php echo $master_id ?>">
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Select</th>
                                <!--                                <th nowrap>Reference</th>-->
                                <th>Title</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $references = referenceList();
                            foreach ($references as $r) {
                                $ref_id = referenceParameter($r, 'bib_key');
                                if (!empty($r)) {
                                    echo '<tr>';
                                    echo '<td><input type="radio" name="bib_key" value="' . $ref_id . '"></td>';
//                                    echo '<td>' . referenceParameter($r, 'bib_title') . '</td>';
                                    echo '<td>' . referenceMessageMsg($r) . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <button id="submit_button" name="submit_button" type="submit"> Map Reference</button>
                    </form>
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
        <!-- end #content -->
        <?php if ($_SESSION['act'] == 'true') { ?>
            <div id="sidebar">
                <div id="sidebar-content">
                    <div id="sidebar-bgbtm">
                        <ul>
                            <li id="s">
                                <h2>Actions</h2>
                                <p style="text-align: center">
                                    <a class="a-button" href="addRef.php?id=<?php echo $master_id ?>">New Reference</a>
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

