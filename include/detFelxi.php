<?php
$returnArray = makeFlexArray($master_id);
$bond = $returnArray[0];
$angle = $returnArray[1];
$dihedral = $returnArray[2];
$dihedral2 = null;
//slice it if dihedral have 5 to more columns
if (sizeof($dihedral[0]) > 12) {
    $dihedral2 = array();
    $temp = array();
    array_push($dihedral2, array_slice($dihedral[0], 12));
    array_push($temp, array_slice($dihedral[0], 0, 12));
    $dihedral = $temp;
}
$contstr = $returnArray[3];
$isBracket = false;
?>

<h3 style="color: #2b2b2b;"><b>Intramolecular Potential Parameters</b></h3>

<!-- bond table-->
<h3 style="color: #55595c;font-size: 17px"><b>Bond</b></h3>
<table width="60%">
    <tr style="border-bottom: solid 1px grey;">
        <td><b>Site-ID's</b></td>
        <td><b>Site-namess</b></td>
        <td><b>Distance / <span>&#8491;</span></b></td>
        <td><b>k<sub>bond</sub>/k<sub>B</sub> / K<span>&#8491;</span><sup>-2</sup> </b></td>
    </tr>

    <?php
    //    $isBracket = true;
    foreach ($bond as $row) { ?>
        <tr>
            <td><?php echo $row[0] ?></td>
            <td><?php echo toSubstanceTitle($row[1]) ?></td>
            <td><?php echo $row[2] ?></td>
            <td><?php echo $row[3] ?></td>
            <?php if ($isBracket) {
                $line = sizeof($bond);
                ?>
                <td rowspan="<?php echo $line ?>">
                    <img src="img/bracket_b.png"
                         style=" height: <?php echo getBracketHeight($line) . 'px'; ?>;width: <?php echo getBracketWidth($line) . 'px'; ?> ;">
                </td>
                <td rowspan="<?php echo $line ?>">
                    <b>Bond</b>
                </td>
            <?php } ?>
        </tr>
        <?php $isBracket = false;
    } ?>
</table>

<!-- Angle table-->
<br/><br/>
<h3 style="color: #55595c;font-size: 17px"><b>Angle</b></h3>
<table width="50%">
    <tr style="border-bottom: solid 1px grey;">
        <td><b>Site-ID's</b></td>
        <td><b>Site-names</b></td>
        <td><b><span>&alpha;</span> / <span>&#xb0;</span></b></td>
        <td><b>k<sub>angle</sub> /k<sub>B</sub> / K rad<sup>-2</sup> </b></td>
    </tr>
    <?php
    //    $isBracket = true;
    foreach ($angle as $row) { ?>
        <tr>
            <td><?php echo $row[0] ?></td>
            <td><?php echo toSubstanceTitle($row[1]) ?></td>
            <td><?php echo $row[2] ?></td>
            <td><?php echo $row[3] ?></td>
            <?php if ($isBracket) {
                $line = sizeof($angle);
                ?>
                <td rowspan="<?php echo $line ?>">
                    <img src="img/bracket_b.png"
                         style=" height: <?php echo getBracketHeight($line) . 'px'; ?>;width: <?php echo getBracketWidth($line) . 'px'; ?> ;">
                </td>
                <td rowspan="<?php echo $line ?>">
                    <b>Angle</b>
                </td>
            <?php } ?>
        </tr>
        <?php $isBracket = false;
    } ?>
</table>

<!-- Dihedral table-->
<br/><br/>
<h3 style="color: #55595c;font-size: 17px"><b>Dihedral</b></h3>
<table width="100%">
    <?php
    //    $isBracket = true;
    $header = true;
    foreach ($dihedral as $row) {
        if ($header) {
            ?>
            <tr style="border-bottom: solid 1px grey;">
                <?php foreach ($row as $key => $value) { ?>
                    <td><b><?php echo toCustomDihedralHeader($key) ?></b></td>
                <?php } ?>
            </tr>
            <?php $header = false;
        } ?>
        <tr>
            <?php foreach ($row as $key => $value) {
                if ($key == "Site-Name") { ?>
                    <td><?php echo toSubstanceTitle($value) ?></td>
                <?php } else { ?>
                    <td><?php echo $value ?></td>
                <?php } ?>

            <?php } ?>
            <?php if ($isBracket) {
                $line = sizeof($dihedral);
                ?>
                <td rowspan="<?php echo $line ?>">
                    <img src="img/bracket_b.png"
                         style=" height: <?php echo getBracketHeight($line) . 'px'; ?>;width: <?php echo getBracketWidth($line) . 'px'; ?> ;">
                </td>
                <td rowspan="<?php echo $line ?>">
                    <b>Dihedral</b>
                </td>
            <?php } ?>
        </tr>
        <?php $isBracket = false;
    } ?>
</table>
<!-- Dihedral2 if there table-->
<?php if (!empty($dihedral2)) { ?>
    <br/>
    <table align="right" style="min-width: 30%">
        <?php
        //        $isBracket = true;
        $header = true;
        foreach ($dihedral2 as $row) {
            if ($header) {
                ?>
                <tr style="border-bottom: solid 1px grey;">
                    <?php foreach ($row as $key => $value) { ?>
                        <td><b><?php echo toCustomDihedralHeader($key) ?></b></td>
                    <?php } ?>
                </tr>
                <?php $header = false;
            } ?>
            <tr>
                <?php foreach ($row as $key => $value) { ?>
                    <td><?php echo $value ?></td>
                <?php } ?>
                <?php if ($isBracket) {
                    $line = sizeof($dihedral);
                    ?>
                    <td rowspan="<?php echo $line ?>">
                        <img src="img/bracket_b.png"
                             style=" height: <?php echo getBracketHeight($line) . 'px'; ?>;width: <?php echo getBracketWidth($line) . 'px'; ?> ;">
                    </td>
                    <td rowspan="<?php echo $line ?>">
                        <b>Dihedral</b>
                    </td>
                <?php } ?>
            </tr>
            <?php $isBracket = false;
        } ?>
    </table>
<?php } ?>

<!-- Constraint table-->
<br/><br/>
<h3 style="color: #55595c;font-size: 17px;margin-top: 7%"><b>Constraint</b></h3>
<table width="40%">
    <tr style="border-bottom: solid 1px grey;">
        <td><b>Unit-ID</b></td>
        <td><b>Site-ID's</b></td>
        <td><b>Site-names</b></td>
    </tr>
    <?php
    //    $isBracket = true;
    foreach ($contstr as $row) { ?>
        <tr>
            <td><?php echo $row[0] ?></td>
            <td><?php echo $row[2] ?></td>
            <td><?php echo toSubstanceTitle($row[3]) ?></td>
            <?php if ($isBracket) {
                $line = sizeof($contstr);
                ?>
                <td rowspan="<?php echo $line ?>">
                    <img src="img/bracket_b.png"
                         style=" height: <?php echo getBracketHeight($line) . 'px'; ?>;width: <?php echo getBracketWidth($line) . 'px'; ?> ;">
                </td>
                <td rowspan="<?php echo $line ?>">
                    <b>Constraint</b>
                </td>
            <?php } ?>
        </tr>
        <?php $isBracket = false;
    } ?>
</table>