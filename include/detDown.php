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
            <td>
                <?php
                $db = new Database();
                $result = $db->selectRecords('SELECT COUNT(*) FROM pm_down WHERE type = ? AND mol_id = ?', array('ms2', $master_id));
                if ($_SESSION['act'] == 'true')
                    echo '<b>Total Downloads : ' . $result[0][0] . '</b>';
                ?>
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
            <td>
                <?php
                $result = $db->selectRecords('SELECT COUNT(*) FROM pm_down WHERE type = ? AND mol_id = ?', array('ls1', $master_id));
                if ($_SESSION['act'] == 'true')
                    echo '<b>Total Downloads : ' . $result[0][0] . '</b>';
                ?>
            </td>
        </tr>
    <?php }
    if ($lam == 1) { ?>
        <tr>
            <th>
                <i>lammps</i>
            </th>
            <th>:</th>
            <td>
                <a class="a-button"
                   href="include/generateFile.php?id=<?php echo $master_id ?>&typ=lam"><?php echo toSubstanceTitle($substance) . '.zip' ?></a>
            </td>
            <td>
                <?php
                $result = $db->selectRecords('SELECT COUNT(*) FROM pm_down WHERE type = ? AND mol_id = ?', array('lam', $master_id));
                if ($_SESSION['act'] == 'true')
                    echo '<b>Total Downloads : ' . $result[0][0] . '</b>';
                ?>
            </td>
        </tr>
    <?php }
    if ($gro == 1) { ?>
        <tr>
            <th>
                <i>gromacs</i>
            </th>
            <th>:</th>
            <td>
                <a class="a-button"
                   href="include/generateFile.php?id=<?php echo $master_id ?>&typ=gro"><?php echo toSubstanceTitle($substance) . '.zip' ?></a>
            </td>
            <td>
                <?php
                $result = $db->selectRecords('SELECT COUNT(*) FROM pm_down WHERE type = ? AND mol_id = ?', array('gro', $master_id));
                if ($_SESSION['act'] == 'true')
                    echo '<b>Total Downloads : ' . $result[0][0] . '</b>';
                ?>
            </td>
        </tr>
    <?php } ?>
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