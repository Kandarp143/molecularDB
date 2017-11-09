<?php
/*
 * PHP XMLWriter - How to create a simple xml
 */
require_once 'database.php';
require_once 'archiveMake.php';
require_once 'function/fileFunc.php';
require_once 'config.php';

$zipName = '';
$typ = $_GET['typ'];

/* dependent variables*/
$rootDir = null;

if ($typ === 'ms2') {
    $rootDir = rootGenPM;
    $zipName = 'databse_ms2.zip';
    $ext = '.pm';
} elseif ($typ === 'ls1') {
    $rootDir = rootGenLS;
    $zipName = 'database_ls1_mardyn.zip';
    $ext = '.xml';
}

/*Getting Data*/
$db = new Database();
$result = $db->selectRecords('select DISTINCT(master_id),filename from pm_master ORDER by master_id ASC', null);


/* file require fields */
$dirName = $typ . date("Ymdhis");
mkdir($rootDir . $dirName . "/");
$filePath = $rootDir . $dirName . "/";
$zipName = $filePath . $zipName;


//route through each data and each file generate
foreach ($result as $row) {

    // id-name.ext (1-Ar.pm)
    $fileName = $row[0] . '-' . $row[1] . $ext;

    //generating File
    if ($typ === 'ms2') {
        genPMFile($row[0], $filePath, $fileName);
    } elseif ($typ === 'ls1') {
        genLsFile($row[0], $filePath, $fileName);
    }
}


//Generate Zip
$za = new archiveMake();
$res = $za->open($zipName, ZipArchive::CREATE);
if ($res === TRUE) {
    $za->addDir($filePath, basename($filePath));
    $za->close();
} else {
    echo 'Could not create a zip archive';
}


//download prompt
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename= $zipName");
header("Content-Length: filesize($zipName)");
header("Location: $zipName");
?>
