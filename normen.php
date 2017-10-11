<?php
include('include/header.php');
require_once('database.php');
require_once('Vec.php');
require_once('funcation/othFunc.php');
require_once('config.php');

$master_id = normanEg;
$disp_sh = false;
$returnArray = makeZmatrix($master_id, $disp_sh);
$zmatrix = $returnArray['zmatrix'];
$maker = $returnArray['maker'];
?>

<html xmlns="http://www.w3.org/1999/xhtml">
</html>
<head>
    <?php include('include/links.php') ?>
    <link rel="stylesheet" type="text/css" href="css/norman.css" media="screen"/>
    <?php include('include/mathJaxConfig.php') ?>
</head>
<!--
 |
 | id keeps the page blank until after the math is typeset.
 |-->
<body>
<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">
        <div id="content">
            <div class="post" id="hide_page" style="visibility:hidden">
                <h1 class="title">Contents </h1>
                <div class="entry normen">
                    <ol class="nomen_content">
                        <li>
                            <a href="#section1"> Model types</a>
                        </li>
                        <li>
                            <a href="#section2"> Units</a>
                        </li>
                        <li>
                            <a href="#section3"> Z-Matrix</a>
                        </li>
                        <li>
                            <a href="#section4"> Interaction Potentials</a>
                        </li>
                    </ol>
                </div>

                <h1 class="title" id="section1">1 Model types </h1>
                <div class="entry normen">
                    <?php include('include/norm1.php') ?>
                </div>

                <h1 class="title" id="section2">2 Units </h1>
                <div class="entry normen">
                    <?php include('include/norm2.php') ?>
                </div>

                <h1 class="title" id="section3">3 Z-Matrix </h1>
                <div class="entry normen">
                    <?php include('include/norm3.php') ?>
                </div>

                <h1 class="title" id="section4">4 Interaction Potentials</h1>
                <div class="entry normen">
                    <?php include('include/norm4.php') ?>
                </div>

            </div>
        </div>
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
