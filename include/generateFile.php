<?php
require '../database.php';
require_once '../funcation/fileFunc.php';
require_once '../funcation/othFunc.php';
require_once '../Vec.php';
require_once '../archiveMake.php';

//getting data
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$typ = $_GET['typ'];
$db = new Database();
$fileName = $db->selectValue('filename', 'pm_master', 'master_id', $id);
$db = Database::disconnect();

//generating File
if ($typ === 'ms2') {
    $filePath = '../' . rootGenPM;
    $fileName = $fileName . '.pm';
    genPMFile($id, $filePath, $fileName);
    $type = 'text/plain';
} elseif ($typ === 'ls1') {
    $filePath = '../' . rootGenLS;
    $fileName = $fileName . '.xml';
    genLsFile($id, $filePath, $fileName);
    $type = filetype($filePath . $fileName);
} elseif ($typ === 'lam') {
    $type = 'application/zip';
    $filePath = '../' . rootGenLAM;

    /* file require fields */
    $dirName = $fileName . date("Ymdhis");
    mkdir($filePath . $dirName . "/");
    $filePath = $filePath . $dirName . "/";
    $zipName = $filePath . $fileName . '.zip';

    /* generate actual files */
    $lam = genLAMmolFile($id, $filePath, $fileName . '.molecule');
    genLAMintFile($id, $filePath, $fileName . '.int', $lam);

    /* archive (zip) generated files into dir */
    $za = new archiveMake();
    $res = $za->open($zipName, ZipArchive::CREATE);
    if ($res === TRUE) {
        $za->addDir($filePath, basename($filePath));
        $za->close();
    } else {
        echo 'Could not create a zip archive';
    }
} elseif ($typ === 'gro') {
    $type = 'application/zip';
    $filePath = '../' . rootGenGRO;
    /* file require fields */
    $dirName = $fileName . date("Ymdhis");
    mkdir($filePath . $dirName . "/");
    $filePath = $filePath . $dirName . "/";
    $zipName = $filePath . $fileName . '.zip';


    /* generate actual files */

    genGROffitpFile($filePath, 'forcefield.itp');
    genGROmolitpFile($id, $filePath, $fileName . '.itp');
    genGROpdbFile($id, $filePath, $fileName . '.pdb');
    genGROatpFile($id, $filePath, 'atomtypes.atp');


    /* archive (zip) generated files into dir */
    $za = new archiveMake();
    $res = $za->open($zipName, ZipArchive::CREATE);
    if ($res === TRUE) {
        $za->addDir($filePath, basename($filePath));
        $za->close();
    } else {
        echo 'Could not create a zip archive';
    }
}


//insert into db
$db = new Database();
$db->insert('INSERT INTO pm_down VALUES (?,?,?,NOW())', array($_SERVER['REMOTE_ADDR'], $id, $typ));

//popup_ attachment
header("Content-disposition: attachment; filename= $fileName");
header("Content-type: $type");
header('Pragma: no-cache');
header('Expires: 0');
set_time_limit(0);
if ($typ === 'lam' || $typ === 'gro') {
    //for ZIP file
    header("Location: $zipName");
} else {
    //for Text file
    readfile($filePath . $fileName);
}

