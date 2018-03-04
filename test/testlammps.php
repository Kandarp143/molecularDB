<?php
require '../database.php';
require_once '../funcation/fileFunc.php';
require_once '../funcation/othFunc.php';
require_once '../Vec.php';
require_once '../archiveMake.php';


//$masterId = 21;
//$points = getMolecule($masterId);
//$points = removeQuad(splitMolSiteWise($points));
//
//
//$lam = genLAMmolFile($masterId, null, '' . '.molecule');
//var_dump($points['dp']);
//var_dump($lam);

$db = new Database();
$result = $db->selectRecords('select DISTINCT(master_id),filename,ms2,ls1,lam,gro from pm_master ORDER by master_id ASC', null);
var_dump($result);