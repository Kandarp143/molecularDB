<?php
/*
 * PHP XMLWriter - How to create a simple xml
 */
require_once 'database.php';
require_once 'archiveMake.php';
require_once 'funcation/fileFunc.php';
require_once 'funcation/othFunc.php';
require_once 'Vec.php';
require_once 'config.php';

$zipName = '';
$typ = $_GET['typ'];

/* dependent variables*/
$rootDir = null;

if ($typ === 'ms2') {
    $rootDir = rootGenPM;
    // echo '$rootDir : ' . $rootDir . "<br>";
    $zipName = 'databse_ms2.zip';
    $ext = '.pm';
} elseif ($typ === 'ls1') {
    $rootDir = rootGenLS;
    $zipName = 'database_ls1_mardyn.zip';
    $ext = '.xml';
} elseif ($typ === 'lam') {
    $rootDir = rootGenLAM;
    $zipName = 'database_lammps.zip';
    $ext = '';
} elseif ($typ === 'gro') {
    $rootDir = rootGenGRO;
    $zipName = 'database_gromacs.zip';
    $ext = '';
}

/*Getting Data*/
$db = new Database();
$result = $db->selectRecords('select DISTINCT(master_id),filename,ms2,ls1,lam,gro from pm_master ORDER by master_id ASC', null);


/* file require fields */
$dirName = $typ . date("Ymdhis");
// echo '$dirName : ' . $dirName . "<br>";
mkdir($rootDir . $dirName . "/");
$filePath = $rootDir . $dirName . "/";
// echo '$filePath : ' . $filePath . "<br>";
$zipName = $filePath . $zipName;
// echo '$zipName : ' . $zipName . "<br>";


//route through each data and each file generate
foreach ($result as $row) {
    $id = $row[0];
    $name = $row[1];
    // id-name.ext (1-Ar.pm)
    $fileName = $id . '-' . $name . $ext;
    //generating File
    if ($typ === 'ms2' && $row['ms2'] == 1) {
        genPMFile($id, $filePath, $fileName);
    } elseif ($typ === 'ls1' && $row['ls1'] == 1) {
        genLsFile($id, $filePath, $fileName);
    } elseif ($typ === 'lam' && $row['lam'] == 1) {
        /*create folder for each molecule*/
        $localFolder = $filePath . $id . '.' . $name . "/";
        mkdir($localFolder);
        /*generate files in respective folder*/
        $lam = genLAMmolFile($id, $localFolder, $fileName . '.molecule');
        genLAMintFile($id, $localFolder, $fileName . '.int', $lam);
        /*go one dir up for next folder*/
        $localFolder = $localFolder . '../';
    } elseif ($typ === 'gro' && $row['gro'] == 1) {
        /*create folder for each molecule*/
        $localFolder = $filePath . $id . '.' . $name . "/";
        mkdir($localFolder);
        /*generate files in respective folder*/
        genGROffitpFile($localFolder, 'forcefield.itp');
        genGROmolitpFile($id, $localFolder, $fileName . '.itp');
        genGROpdbFile($id, $localFolder, $fileName . '.pdb');
        genGROatpFile($id, $localFolder, 'atomtypes.atp');
        /*go one dir up for next folder*/
        $localFolder = $localFolder . '../';
    }
}


//Generate Zip
$za = new archiveMake();
$res = $za->open($zipName, ZipArchive::CREATE);
// echo 'addDir : ' . $filePath . "<br>";
// echo 'addDir : ' . basename($filePath) . "<br>";
if ($res === TRUE) {
    $za->addDir($filePath, basename($filePath));
    $za->close();
} else {
    // echo 'Could not create a zip archive';
}

//insert into db
$db->insert('INSERT INTO pm_down VALUES (?,?,?,NOW())', array($_SERVER['REMOTE_ADDR'], 0, $typ));

//download prompt
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename= $zipName");
header("Content-Length: filesize($zipName)");
header("Location: $zipName");
?>
