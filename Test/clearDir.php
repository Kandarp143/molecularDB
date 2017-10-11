<?php
/*
 * PHP XMLWriter - How to create a simple xml
 */
require_once 'database.php';
require_once 'archiveMakehp';
require_once 'funcation/fileFunc.php';
require_once 'config.php';

try {
    clearDirectory(rootGenLS);
    clearDirectory(rootGenPM);
    clearDirectory(rootLog);
    clearDirectory(rootProfileImg);
    var_dump('Done !');
} catch (Exception $e) {
    var_dump($e);
}

?>