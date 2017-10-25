<table>
    <?php if ($ms2 == 1) { ?>

        <tr>
            <th>
                <b><i>ms2</i></b>
            </th>
            <th>
                :
            </th>
            <td>
                <a class="a-button"
                   href="include/generateFile.php?id=<?php echo $master_id ?>&typ=ms2"><?php echo toSubstanceTitle($substance) . '.pm' ?></a>
            </td>
        </tr>
    <?php }
    if ($ls1 == 1) { ?>
        <tr>
            <th>
                <i>ls1 mardyn</i>
            </th>
            <th>:</th>
            <td>
                <a class="a-button"
                   href="include/generateFile.php?id=<?php echo $master_id ?>&typ=ls1"><?php echo toSubstanceTitle($substance) . '.xml' ?></a>
            </td>
        </tr>
    <?php } ?>
</table>