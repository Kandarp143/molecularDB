<style>
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

</style>
<?php
// open for user
if (!isset($_SESSION['usr'])) {
    $_SESSION['usr'] = 'user';
    $_SESSION['act'] = 'false';
}
?>
<div id="header">
    <div id="logo">
        <h2><a href="welcome.php">Molecular Models of Boltzmann-Zuse Society</a></h2>
        <!--   note open for user     -->
        <?php //if (isset($_SESSION['usr'])) { ?>
        <!--   open for user     -->
        <?php if ($_SESSION['usr'] != 'user') { ?>
            <p><strong>&nbsp;<span
                            style="color: #32CD32">&nbsp;&nbsp;Welcome <b><?php echo $_SESSION['usr'] ?></b></span>
                    <a href="logout.php">( Logout )</a></strong></p>
        <?php } else { ?>
            <p>&nbsp;&nbsp;<strong>&nbsp;<a href="index.php" style="text-decoration: underline;">[Sign In]</a>
                </strong>(Public Mode)</p>
        <?php } ?>

    </div>
    <!-- end #logo -->
    <?php if (isset($_SESSION['usr'])) { ?>
        <div id="menu">
            <ul>
                <?php if (isset($_SESSION['usr']) && $_SESSION['act'] == 'true') { ?>
                    <li class="dropdown"><a href="#" style="color: #32CD32;border: solid 1px #32CD32;">Admin Panel</a>
                        <div class="dropdown-content">
                            <a href="addmol.php" style="color: #4CAF50">New Molecule</a>
                            <a href="addRef.php" style="color: #4CAF50">New Reference</a>
                        </div>
                    </li>
                <?php } ?>
                <li><a href="welcome.php">Home</a></li>
                <li><a href="mollist.php">Molecule List</a></li>
                <li><a href="reflist.php">References</a></li>
                <li><a href="normen.php">Nomenclature</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    <?php } ?>
    <!-- end #menu -->
</div>
<!-- end #header -->
<?php
require_once 'config.php';
if (serverName == 'kpatel.bplaced.nets') {
    echo '<span style="color: red;text-align: center;">This is test server :  kpatel.bplaced.net</span>';
} elseif (serverName == 'localhost') {
    echo '<span style="color: red;text-align: center;">This is test server :  localhost</span>';
}
?>