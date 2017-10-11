<?php include('include/header.php') ?>
<?php
require_once 'database.php';
$ref = array(
    'Bib_Title' => '', 'Title' => '', 'Author' => '', 'Journal' => '', 'Publisher' => '', 'Volume' => '', 'Number' => '',
    'Pages' => '', 'Year' => '', 'Editor' => '', 'Edition' => '', 'Url' => '', 'Doi' => ''
);
$ref_id = 0;
$ref_id = $_REQUEST['id'];
$db = new Database();
$result = $db->selectRecords('SELECT DISTINCT * FROM pm_bib WHERE  pm_bib.bib_key =?', array($ref_id));
foreach ($result as $row) {
    $ref['Bib_Title'] = $row['bib_title'];
    $ref[$row['param']] = $row['value'];
}


?>
<link rel="stylesheet" type="text/css" href="css/tooltip.css" media="screen"/>
<html>
<head> <?php include('include/links.php') ?>
</head>
<body>
<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <h1 class="title">Add new Reference</h1>
                <form action="processUpRef.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : 0; ?>" method="post">
                    <table>
                        <tr>
                            <td>Shorthand Title<span class="msg-err"><b>*</b></span></td>
                            <td><input name="bib_title" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Bib_Title'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Title of Publication<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Title" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Title'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Author<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Author" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Author'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Journal<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Journal" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Journal'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Publisher<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Publisher" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Publisher'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Volume<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Volume" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Volume'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Number<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Number" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Number'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Pages<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Pages" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Pages'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Year<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Year" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Year'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>DOI<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Doi" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Doi'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Editor</td>
                            <td><input name="Editor" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Editor'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Edition</td>
                            <td><input name="Edition" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Edition'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Url<span class="msg-err"><b>*</b></span></td>
                            <td><input name="Url" type="text"
                                       placeholder=""
                                       value="<?php echo $ref['Url'] ?>"
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" id="mstsub"><strong>Update</strong></button>
                        </tr>
                    </table>
                </form>
                <?php
                $sParam = 'processUpRef';  /*page name of processor*/
                $sMsg = 'Reference Successfully Updated !';
                if (isset($_SESSION[$sParam])) {
                    if (!$_SESSION[$sParam]['success']) {
                        echo '<p class="msg-err"> Errors [';
                        foreach ($_SESSION[$sParam]['errors'] as $err) {
                            echo $err . ', ';
                        }
                        echo ']</p>';

                    } else {
                        echo '<br/><br/><h3 class="msg-suc">' . $sMsg . ' with ID :' . $_SESSION[$sParam]['id'] . ' </h3>' .
                            '<a href="reflist.php" class="a-btn">Go to Reference List</a> ';
                    }
                    unset($_SESSION[$sParam]);
                }
                ?>
            </div>
        </div>
        <?php if ($_SESSION['act'] == 'true') { ?>
            <div id="sidebar">
                <div id="sidebar-content">
                    <div id="sidebar-bgbtm">
                        <ul>
                            <li id="s">
                                <h2>Actions</h2>
                                <p style="text-align: center">
                                    <a class="a-button" href="reflist.php">
                                        Back to Reference List</a>
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end #sidebar -->
        <?php } ?>
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

