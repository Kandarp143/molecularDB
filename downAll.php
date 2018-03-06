<?php include('include/header.php') ?>
<?php
require_once 'Vec.php';
require_once 'database.php';
require_once 'funcation/othFunc.php';
$db = new Database();
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
                                <a class="a-button" id="ms2" onClick="reply_click(this.id)"
                                   href="processDownAll.php?typ=ms2"><?php echo 'database_<i>ms2</i>.zip' ?></a>
                            </td>
                            <td>
                                <?php
                                $result = $db->selectRecords('SELECT COUNT(*) FROM pm_down WHERE type = ? AND mol_id=0', array('ms2'));
                                if ($_SESSION['act'] == 'true')
                                    echo '<b>Total Downloads : ' . $result[0][0] . '</b>';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <b>ls1 mardyn</b>
                            </th>
                            <th>
                                :
                            </th>
                            <td>
                                <a class="a-button" id="ls1" onClick="reply_click(this.id)"
                                   href="processDownAll.php?typ=ls1"><?php echo 'database_ls1_mardyn.zip' ?></a>
                            </td>
                            <td>
                                <?php
                                $result = $db->selectRecords('SELECT COUNT(*) FROM pm_down WHERE type = ? AND mol_id=0', array('ls1'));
                                if ($_SESSION['act'] == 'true')
                                    echo '<b>Total Downloads : ' . $result[0][0] . '</b>';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <b>lammps</b>
                            </th>
                            <th>
                                :
                            </th>
                            <td>
                                <a class="a-button" id="lammps" onClick="reply_click(this.id)"
                                   href="processDownAll.php?typ=lam"><?php echo 'database_lammps.zip' ?></a>
                            </td>
                            <td>
                                <?php
                                $result = $db->selectRecords('SELECT COUNT(*) FROM pm_down WHERE type = ? AND mol_id=0 ', array('lam'));
                                if ($_SESSION['act'] == 'true')
                                    echo '<b>Total Downloads : ' . $result[0][0] . '</b>';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <b>gromacs</b>
                            </th>
                            <th>
                                :
                            </th>
                            <td>
                                <a class="a-button" id="gromacs" onClick="reply_click(this.id)"
                                   href="processDownAll.php?typ=gro"><?php echo 'database_gromacs.zip' ?></a>
                            </td>
                            <td>
                                <?php
                                $result = $db->selectRecords('SELECT COUNT(*) FROM pm_down WHERE type = ? AND mol_id = 0', array('gro'));
                                if ($_SESSION['act'] == 'true')
                                    echo '<b>Total Downloads : ' . $result[0][0] . '</b>';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <?php if ($_SESSION['act'] == 'true') { ?>
                                <td colspan="4">
                                    <button onclick="myFunction()">Reload Downloads Count</button>
                                    <script>
                                        function myFunction() {
                                            location.reload();
                                        }
                                    </script>
                                </td>
                            <?php } ?>
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
<script>
    function reply_click(clicked_id) {
        var org = document.getElementById(clicked_id).innerHTML;
        var divs = document.querySelectorAll('.a-button');
        [].forEach.call(divs, function (div) {
            // do whatever
            div.setAttribute("class", "disabled");
        });
        document.getElementById(clicked_id).innerText = 'Processing..';
        setInterval(function () {
            if (document.readyState == 'complete')
                [].forEach.call(divs, function (div) {
                    div.classList.remove("disabled");
                    div.setAttribute("class", "a-button");

                });
            document.getElementById(clicked_id).innerHTML = org;
        }, 3000);

    }
</script>

</body>
</html>

