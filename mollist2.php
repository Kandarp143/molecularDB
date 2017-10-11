<?php include('include/header.php');
require_once 'funcation/othFunc.php'; ?>
<!-- Design by Kandarp -->
<html>
<head> <?php include('include/links.php') ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"
          media="screen"/>
    <!--javascript-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.15/pagination/input.js"></script>
    <script src="js/mollist2.js"></script>
</head>
<body>


<div id="wrapper">
    <?php include('include/nav.php') ?>
    <?php
    include 'database.php';

    $pdo = Database::connect();
    if ($_SESSION['act'] == 'true') {
        $tbl_sql = 'SELECT * FROM pm_master';
    } else {
        $tbl_sql = 'SELECT * FROM pm_master where type ="Rigid"';
    }

    ?>
    <div id="page">
        <div style="width: 98%;margin: 0 auto;">
            <table id="listmol" class="display" cellspacing="0" width="95%">
                <thead>
                <tr>
                    <td colspan="4" style="border: none;">
                        <a id="reload" href="#" class="a-button">Restore View</a>
                    </td>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Substance</th>
                    <th nowrap>CAS-No</th>
                    <th>Name</th>
                    <th>LJ</th>
                    <th>Charge</th>
                    <th>Dipole</th>
                    <th>Quadrupole</th>
                    <th>References</th>
                    <th>Type</th>
                    <?php if ($_SESSION['act'] == 'true') { ?>
                        <th><span style="color: green"><b>Action</b></span></th>
                    <?php } ?>

                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Substance</th>
                    <th nowrap>CAS-No</th>
                    <th>Name</th>
                    <th>LJ</th>
                    <th>Charge</th>
                    <th>Dipole</th>
                    <th>Quadrupole</th>
                    <th>References</th>
                    <th>Type</th>
                    <?php if ($_SESSION['act'] == 'true') { ?>
                        <th></th>
                    <?php } ?>

                </tr>
                </tfoot>
                <tbody>

                <!-- table print-->
                <?php
                foreach ($pdo->query($tbl_sql) as $row) {
                    if ($_SESSION['act'] == 'true') {
                        if (isSubstanceIonic($row['filename'])) {
                            echo "<tr style='text-align: left;color: #008CBA;'>";
                        } else {
                            echo "<tr style='text-align: left;'>";
                        }

                    } else {
                        if (isSubstanceIonic($row['filename'])) {
                            echo "<tr style='text-align: left;line-height: 23px;color: #008CBA;'>";
                        } else {
                            echo "<tr style='text-align: left;line-height: 23px;'>";
                        }
                    }

                    echo "<td style='border: solid 1px red'>" . $row['display_id'] . "</td>";
                    $substance = toSubstanceTitle($row['filename']);
                    echo "<td><a onclick='setState()' href='moldetail.php?id=" . $row['master_id'] . "'>"
                        . $substance
                        . "</a></td>";
                    echo "<td nowrap>" . $row['cas_no'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td style='border: solid 1px red'>" . getModelType($row['lj'], 0, 0, 0) . "</td>";
                    echo "<td style='border: solid 1px red'>" . getModelType(0, $row['charge'], 0, 0) . "</td>";
                    echo "<td style='border: solid 1px red'>" . getModelType(0, 0, $row['dipole'], 0) . "</td>";
                    echo "<td style='border: solid 1px red'>" . getModelType(0, 0, 0, $row['quadrupole']) . "</td>";
                    echo "<td nowrap> [" . $row['bibtex_key'] . "] </tdnowrap>";
                    echo "<td>" . $row['type'] . "</td>";
                    if ($_SESSION['act'] == 'true') {
                        echo '<td><a class="a-success"  href="updatemol.php?id=' . $row['master_id'] . '">Update</a><br/>';
                        echo '<a  class="a-danger" href="deletemol.php?id=' . $row['master_id'] . '">Delete</a>';
                        echo '</td>';
                    }
                    echo "</tr>";
                }
                Database::disconnect();
                ?>
                </tbody>
            </table>
            <script>
                function setState() {
                    var ids = Array();
                    Object.keys(data).forEach(function (key) {
                        if (!isNaN(data[key][0])) {
                            ids.push(data[key][0]);
                        }
                    });
                    window.localStorage.setItem("stored_ids", JSON.stringify(ids));
                    // alert(ids);
                }
            </script>
        </div>
    </div>
    <div style="clear:both; margin:0;"></div>
</div>
<!-- end #page -->


<div id="footer">
    <?php include('include/footer.php') ?>
</div>
<!-- end #footer -->
</body>
</html>


