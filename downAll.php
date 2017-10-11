<?php include('include/header.php') ?>
<?php
require_once 'Vec.php';
require_once 'database.php';
require_once 'funcation/othFunc.php';
?>
<!-- Design by Kandarp -->
<html>
<head> <?php include('include/links.php') ?>
    <style>
        /* disable hyperlink after click */
        .disabled {
            opacity: 0.5;
            pointer-events: none;
            cursor: default;
        }
    </style>
</head>
<body>

<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <h1 class="title">Download Molecular Database of Boltzmann-Zuse Society</h1>
                <div class="entry">
                    <p>
                    <table>
                        <tr>
                            <th>
                                <b><i>ms2</i></b>
                            </th>
                            <th>:</th>
                            <td>
                                <a class="a-button" id="ms2"
                                   href="processDownAll.php?typ=ms2"><?php echo 'database_<i>ms2</i>.zip' ?></a>
                            </td>
                            <script>
                                $("#ms2").click(function () {
                                    $(this).addClass('disabled');
                                    $(this).html('Processing..');
                                    $('#ls1').addClass('disabled');
                                    var delayMillis = 5000; //1 second
                                    setTimeout(function () {
                                        $('#ls1').removeClass('disabled');
                                        $('#ms2').removeClass('disabled');
                                        $('#ms2').html('database_<i>ms2</i>.zip');
                                    }, delayMillis);
                                });
                            </script>
                        </tr>
                        <tr>
                            <th>
                                <b>ls1 mardyn</b>
                            </th>
                            <th>
                                :
                            </th>
                            <td>
                                <a class="a-button" id="ls1"
                                   href="processDownAll.php?typ=ls1"><?php echo 'database_ls1_mardyn.zip' ?></a>
                            </td>
                            <script>
                                $("#ls1").click(function () {
                                    $(this).addClass('disabled');
                                    $(this).html('Processing..');
                                    $('#ms2').addClass('disabled');
                                    var delayMillis = 5000; //1 second
                                    setTimeout(function () {
                                        $('#ms2').removeClass('disabled');
                                        $('#ls1').removeClass('disabled');
                                        $('#ls1').html('database_ls1_mardyn.zip');
                                    }, delayMillis);
                                });
                            </script>

                        </tr>
                    </table>
                    </p>
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

