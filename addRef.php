<?php include('include/header.php') ?>
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
                <form action="processInsRef.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : 0; ?>" method="post">
                    <table>
                        <tr>
                            <td>Shorthand Title<span class="msg-err"><b>*</b></span></td>
                            <td><input name="bib_title" type="text"
                                       placeholder=""
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
                                       size="50"></td>
                            <td>
                                <div class="tooltip">[i]
                                    <span class="tooltiptext">Tooltip text</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" id="mstsub"><strong>Add Reference</strong></button>
                        </tr>
                    </table>
                </form>
                <?php
                $sParam = 'processInsRef';  /*page name of processor*/
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

