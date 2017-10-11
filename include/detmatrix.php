<?php
$returnArray = makeZmatrix($master_id, $disp_sh);
$zmatrix = $returnArray['zmatrix'];
$pmatrix = $returnArray['pmatrix'];
$maker = $returnArray['maker'];
$maker2 = $returnArray['maker2'];
?>

<h3 style="color: #2b2b2b"><b>Geometry in Z-Matrix</b></h3>

<table width="80%">
    <tr style="border-bottom: solid 1px grey;">
        <td><b>Site-ID</b></td>
        <td><b>Site-name</b></td>
        <td><b>Ref.</b></td>
        <td><b>Distance / <span>&#8491;</span></b></td>
        <td><b>Ref.</b></td>
        <td><b>Angle / <span>&#xb0;</span></b></td>
        <td><b>Ref.</b></td>
        <td><b>Dihedral / <span>&#xb0;</span></b></td>
    </tr>
    <?php
    foreach ($zmatrix as $z):?>
        <?php if (array_key_exists($z[1], $maker)) { ?>
            <tr>
                <td colspan="8"></td>
            </tr>
        <?php } ?>
        <tr>
            <td><?php echo $z[9] ?></td>
            <td><?php echo toSubstanceTitle($z[2]) ?></td>
            <td><?php echo $z[3] ?></td>
            <td><?php echo $z[4] ?></td>
            <td><?php echo $z[5] ?></td>
            <td><?php echo $z[6] ?></td>
            <td><?php echo $z[7] ?></td>
            <td><?php echo $z[8] ?></td>
            <?php
            if (array_key_exists($z[1], $maker)) {
                $line = $maker[$z[1]];
                $hMul = 22;
                $wMul = 0.33;
                if ($line == 1) {
                    $fhight2 = '30';;
                    $fwidth2 = '12';
                } elseif ($line == 2) {
                    $fhight2 = $hMul * $line;
                    $fwidth2 = $hMul * $line * 0.45;
                } elseif ($line > 7) {
                    $fhight2 = $hMul * $line;
                    $fwidth2 = $hMul * $line * 0.20;
                } else {
                    $fhight2 = $hMul * $line;
                    $fwidth2 = $hMul * $line * 0.30;
                }
                ?>
                <td rowspan=" <?php echo $line ?>">
                    <img src="img/bracket_b.png"
                         style=" height: <?php echo $fhight2 . 'px'; ?>;width: <?php echo $fwidth2 . 'px'; ?> ;">
                </td>
                <td rowspan=" <?php echo $line ?>"><b><?php echo toFormatSiteType($z[0]) ?></b>
                </td>
            <?php } ?>
        </tr>

        <?php
    endforeach; ?>
</table>


<h3 style="color: #2b2b2b;margin-top: 5%"><b>Intermolecular Potential Parameters</b></h3>


<table width="80%">
    <?php $isheader = false;
    foreach ($pmatrix as $z):?>
        <?php if (array_key_exists($z[1], $maker2)) {
            $isheader = true; ?>
            <tr>
                <td></td>
            </tr>
        <?php }
        if ($isheader) { ?>
            <tr style="border-bottom: solid 1px grey;">
                <?php foreach ($z[2] as $paramName => $value) { ?>
                    <td><b><?php echo toCustomHeader($paramName) ?></b></td>
                <?php } ?>
            </tr>
        <?php }
        $isheader = false; ?>
        <tr>
            <?php foreach ($z[2] as $paramName => $value) { ?>
                <td><?php echo $paramName == 'SiteName' ? toSubstanceTitle($value) : $value; ?>
                </td>
            <?php }
            if (array_key_exists($z[1], $maker2)) {
                $line = $maker2[$z[1]];
                $hMul = 22;
                $wMul = 0.33;
                if ($line == 1) {
                    $fhight2 = '30';
                    $fwidth2 = '12';
                } elseif ($line == 2) {
                    $fhight2 = $hMul * $line;
                    $fwidth2 = $hMul * $line * 0.45;
                } elseif ($line > 7) {
                    $fhight2 = $hMul * $line;
                    $fwidth2 = $hMul * $line * 0.20;
                } else {
                    $fhight2 = $hMul * $line;
                    $fwidth2 = $hMul * $line * 0.30;
                }
                ?>
                <td rowspan=" <?php echo $maker2[$z[1]] ?>">
                    <img src="img/bracket_b.png"
                         style=" height: <?php echo $fhight2 . 'px'; ?>;width: <?php echo $fwidth2 . 'px'; ?> ;">
                </td>
                <td rowspan=" <?php echo $maker2[$z[1]] ?>"><b><?php echo toFormatSiteType($z[0]) ?></b>
                </td>
            <?php } ?>
        </tr>
        <?php
    endforeach; ?>
</table>