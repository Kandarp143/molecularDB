<?php
require '../database.php';
require_once '../funcation/fileFunc.php';

//getting data
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$typ = $_GET['typ'];

/* dependent variables*/
$rootDir = null;
$ext = null;

if ($typ === 'ms2') {
    $rootDir = rootGenPM;
    $ext = '.pm';
} elseif ($typ === 'ls1') {
    $rootDir = rootGenLS;
    $ext = '.xml';
}


//file require fields
$db = new Database();
$filePath = '../' . $rootDir;
$fileName = $db->selectValue('filename', 'pm_master', 'master_id', $id);
$fileName = $fileName . $ext;
$actualFile = $filePath . $fileName;
$db = Database::disconnect();

//generating File
if ($typ === 'ms2') {
    genPMFile($id, $filePath, $fileName);
    $type = 'text/plain';
} elseif ($typ === 'ls1') {
    genLsFile($id, $filePath, $fileName);
    $type = filetype($actualFile);
}

//popup_ attachment
header("Content-disposition: attachment; filename= $fileName");
header("Content-type: $type");
header('Pragma: no-cache');
header('Expires: 0');
set_time_limit(0);
readfile($actualFile);