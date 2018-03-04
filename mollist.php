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
    <script src="//cdn.datatables.net/plug-ins/1.10.16/sorting/natural.js"></script>
    <script src="js/list.js"></script>
    <!--custom style for data tabels-->
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin-left: 10px;
            border: 1px solid gray;
            font-weight: bold;
        }

        .paginate_input {
            width: 50px;
        }

        .previous {
            margin-left: 10px;
        }

        .paginate_page {
            margin-left: 10px;
        }

        .ext {
            display: none;
        }
    </style>
</head>
<body>

<!-- start #page -->
<div id="wrapper">
    <?php include('include/nav.php') ?>
    <?php
    include 'database.php';
    $pdo = Database::connect();
    if ($_SESSION['act'] == 'true') {
        $tbl_sql = "SELECT
    master_id,
    display_id,
    filename,
    cas_no,
    NAME,
    bibtex_key,
    IF(lj = 0, '', IF(lj = 1, CONCAT(lj,' L.J. Site'),CONCAT(lj,' L.J. Sites'))) as lj,
    IF(dipole = 0, '', IF(dipole = 1, CONCAT(dipole,'  Dipole'), CONCAT(dipole,'  Dipoles'))) as dipole,
    IF(charge = 0, '', IF(charge = 1, CONCAT(charge,' Charge'), CONCAT(charge,' Charges'))) as charge,
    IF(quadrupole = 0,'',IF(quadrupole = 1, CONCAT(quadrupole,' Quadrupole'), CONCAT(quadrupole,' Quadrupoles'))) as quadrupole,
    memory_loc,
	bibtex_ref_key,
    disp_sh,
    name,
    type
FROM
    pm_master ORDER BY display_id ASC";
    } else {
        $tbl_sql = "SELECT
    master_id,
    display_id,
    filename,
    cas_no,
    NAME,
    bibtex_key,
    IF(lj = 0, '', IF(lj = 1, CONCAT(lj,' L.J. Site'),CONCAT(lj,' L.J. Sites'))) as lj,
    IF(dipole = 0, '', IF(dipole = 1, CONCAT(dipole,'  Dipole'), CONCAT(dipole,'  Dipoles'))) as dipole,
    IF(charge = 0, '', IF(charge = 1, CONCAT(charge,' Charge'), CONCAT(charge,' Charges'))) as charge,
    IF(quadrupole = 0,'',IF(quadrupole = 1, CONCAT(quadrupole,' Quadrupole'), CONCAT(quadrupole,' Quadrupoles'))) as quadrupole,
    memory_loc,
	bibtex_ref_key,
    disp_sh,
    name,
    type
FROM
    pm_master where user_mode = 1 ORDER BY display_id ASC";
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
                    <th class="ext">MST</th>
                    <?php if ($_SESSION['act'] == 'true') { ?>
                        <th>ID<br/>[DB-ID]</th>
                    <?php } else { ?>
                        <th>ID</th>
                    <?php } ?>
                    <th>Substance</th>
                    <th nowrap>CAS-No</th>
                    <th>Name</th>
                    <th>Lennard-Jones</th>
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
                    <th class="ext">MST</th>
                    <th>ID</th>
                    <th>Substance</th>
                    <th nowrap>CAS-No</th>
                    <th>Name</th>
                    <th>Lennard-Jones</th>
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
                    $db = new Database();
                    $bib = $db->selectValue('bib_title', 'pm_bib', 'bib_key', $row['bibtex_ref_key']);
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

                    //extra first column for save state
                    echo "<td class='ext'>" . $row['master_id'] . "</td>";

                    if ($_SESSION['act'] == 'true') {
                        echo "<td>" . $row['display_id'] . " [" . $row['master_id'] . "]" . "</td>";
                    } else {
                        echo "<td>" . $row['display_id'] . "</td>";
                    }
                    $substance = toSubstanceTitle($row['filename']);
                    echo "<td><a onclick='setState()' href='moldetail.php?id=" . $row['master_id'] . "'>"
                        . $substance
                        . "</a></td>";
                    echo "<td nowrap>" . $row['cas_no'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td >" . $row['lj'] . "</td>";
                    echo "<td >" . $row['charge'] . "</td>";
                    echo "<td >" . $row['dipole'] . "</td>";
                    echo "<td >" . $row['quadrupole'] . "</td>";
                    echo "<td nowrap> [" . $bib . "] </tdnowrap>";
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

<!-- start #footer -->
<div id="footer">
    <?php include('include/footer.php') ?>
</div>
<!-- end #footer -->
</body>
</html>


