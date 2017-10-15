<link rel="stylesheet" type="text/css" href="css/tooltip.css" media="screen"/>
<form action="processUpdateHead.php?id=<?php echo $master_id ?>" method="post" enctype="multipart/form-data"
      class="form">
    <table width="100%" class="beta">
        <tr>
            <td>Database ID</td>
            <td><?php echo $master_id ?></td>
        </tr>
        <tr>
            <td>Display ID<span class="msg-err"><b>*</b></span></td>
            <td><input name="displayId" type="text"
                       value="<?php echo intval($dis_id); ?>"
                       size="10"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>Substance<span class="msg-err"><b>*</b></span></td>
            <td><input name="substance" type="text"
                       value="<?php echo !empty($substance) ? $substance : ''; ?>"
                       size="10"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
            <td rowspan="5" width="50%">
                <div style="margin-left: 50%">
                    <b>Update Picture</b><br/><br/>
                    <input type="file" name="profile" id="profile"><br/><br/>
                    <img height="150px"
                         src="<?php echo 'img/profile/PM-' . $master_id . '.png' ?>"
                         alt="Image not found"
                         onerror="this.onerror=null;this.src='img/NoImgFound.gif';"
                    />
                </div>

            </td>
        </tr>
        <tr>
            <td>CAS-No<span class="msg-err"><b>*</b></span></td>
            <td><input name="casno" type="text"
                       value="<?php echo !empty($casno) ? $casno : ''; ?>"
                       size="10"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>

        </tr>
        <tr>
            <td>Name<span class="msg-err"><b>*</b></span></td>
            <td><input name="name" type="text"
                       value="<?php echo !empty($name) ? $name : ''; ?>"
                       size="10"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>Model Type<span class="msg-err"><b>*</b></span></td>
            <td><select id='lj' name="lj">
                    <option value=<?php echo $lj ?>><?php echo $lj ?></option>
                    <?php for ($i = 0; $i <= 20; $i++) { ?>
                        <option value=<?php echo $i ?>><?php echo $i ?></option>
                    <?php } ?>
                </select>L.J. Sites &nbsp;
                <select id='charge' name="charge">
                    <option value=<?php echo $charge ?>><?php echo $charge ?></option>
                    <?php for ($i = 0; $i <= 20; $i++) { ?>
                        <option value=<?php echo $i ?>><?php echo $i ?></option>
                    <?php } ?>
                </select>Charges &nbsp;
                <select id='dipole' name="dipole">
                    <option value=<?php echo $dipole ?>><?php echo $dipole ?></option>
                    <?php for ($i = 0; $i <= 20; $i++) { ?>
                        <option value=<?php echo $i ?>><?php echo $i ?></option>
                    <?php } ?>
                </select>Dipole &nbsp;
                <select id='quadrupole' name="quadrupole">
                    <option value=<?php echo $quadrupole ?>><?php echo $quadrupole ?></option>
                    <?php for ($i = 0; $i <= 20; $i++) { ?>
                        <option value=<?php echo $i ?>><?php echo $i ?></option>
                    <?php } ?>
                </select>Quadrupole
            </td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea name="description" rows="4" cols="50"><?php echo !empty($description) ? $description : ''; ?>
                </textarea>
            </td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>Type<span class="msg-err"><b>*</b></span></td>
            <td><input type="radio" name="type"
                       value="Rigid" <?php echo $type == "Rigid" || empty($type) ? 'checked' : '' ?>>
                Rigid &nbsp
                <input type="radio" name="type" value="Flexible"<?php echo $type == "Flexible" ? 'checked' : '' ?>>
                Flexible
            </td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext"><img src="img/info.ico"></span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">Display Shielding <input type="checkbox" name="disp_sh"
                <?php
                echo $disp_sh == 0 ? '' : 'checked'
                ?>
            </td>
            <td>
                <div class="tooltip"> [i]
                    <span class="tooltiptext"> Tooltip text </span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">Display molecule in User Mode ? <input type="checkbox" name="user_mode"
                <?php
                echo $user_mode == 0 ? '' : 'checked'
                ?>
            </td>
            <td>
                <div class="tooltip"> [i]
                    <span class="tooltiptext"> Tooltip text </span>
                </div>
            </td>
        </tr>
        <tr>
            <td>

            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td colspan="2">
                <button> Update Master Data</button>
            </td>
        </tr>

    </table>

</form>


<!--Display error-->
<?php
$sParam = 'processUpdateHead';  /*page name of processor*/
$sMsg = 'Master data updated successfully !';

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


