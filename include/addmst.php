<link rel="stylesheet" type="text/css" href="css/tooltip.css" media="screen"/>
<form id="msform" action="processInsert.php" enctype="multipart/form-data" method="post" class="form">
    <table width="50%" class="beta">
        <tr>
            <th colspan="3">Step 1 : Insert master data</th>
        </tr>
        <?php
        require_once 'database.php';
        $db = new Database();
        $res = $db->selectRecords('Select max(master_id) from pm_master', null);
        ?>
        <tr>
            <td>Display ID<span class="msg-err"><b>*</b></span></td>
            <td><input name="displayId" type="text" size="10" value="<?php echo $res[0][0] + 1 ?>"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>


        <tr>
            <td>Substance<span class="msg-err"><b>*</b></span></td>
            <td><input name="substance" type="text"
                       size="10"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>CAS-No<span class="msg-err"><b>*</b></span></td>
            <td><input name="casno" type="text"
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
                    <option value=0>L.J. Sites</option>
                    <?php for ($i = 0; $i <= 20; $i++) { ?>
                        <option value=<?php echo $i ?>><?php echo $i ?></option>
                    <?php } ?>
                </select>
                <select id='charge' name="charge">
                    <option value="0"> Charges</option>
                    <?php for ($i = 0; $i <= 20; $i++) { ?>
                        <option value=<?php echo $i ?>><?php echo $i ?></option>
                    <?php } ?>
                </select>
                <select id='dipole' name="dipole">
                    <option value="0"> Dipole</option>
                    <?php for ($i = 0; $i <= 20; $i++) { ?>
                        <option value=<?php echo $i ?>><?php echo $i ?></option>
                    <?php } ?>
                </select>
                <select id='quadrupole' name="quadrupole">
                    <option value="0"> Quadrupole</option>
                    <?php for ($i = 0; $i <= 20; $i++) { ?>
                        <option value=<?php echo $i ?>><?php echo $i ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea name="description" rows="4" cols="50"></textarea>
            </td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>Type<span class="msg-err"><b>*</b></span></td>
            <td><input type="radio" name="type" value="Rigid" checked="checked"> Rigid&nbsp
                <input type="radio" name="type" value="Flexible"> Flexible
            </td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext"><img src="img/info.ico"></span>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <th colspan="3">Step 2 : Additional Configuration</th>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Display Shielding <input type="checkbox" name="disp_sh"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">Display molecule in User Mode ? <input type="checkbox" name="user_mode"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b> Available Download Formats </b>
            </td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext">Tooltip text</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <i>ms2</i> <input type="checkbox" name="ms2">
            </td>
            <td>
                <i>ls1 mardyn</i> <input type="checkbox" name="ls1">
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <th colspan="3">Step 3 : Upload Files</th>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <!--        step 2-->
            <td nowrap="">Molecule image</td>
            <td><input type="file" name="profile" id="profile"></td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext"><img src="img/info.ico"></span>
                </div>
            </td>
        </tr>
        <tr>
            <td nowrap="">
                Force Fields<span class="msg-err"><b>*</b></span> <br/>(PM File)
            </td>
            <td>
                <input type="file" name="pmfile" id="pmfile">
            </td>
            <td>
                <div class="tooltip">[i]
                    <span class="tooltiptext"><img src="img/info.ico"></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td colspan="2">
                <button>Add Molecule</button>
            </td>

        </tr>
    </table>
</form>