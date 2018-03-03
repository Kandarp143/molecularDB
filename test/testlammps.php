<?php
require '../database.php';
require_once '../funcation/fileFunc.php';
require_once '../funcation/othFunc.php';
require_once '../Vec.php';
require_once '../archiveMake.php';


$masterId = 21;
$points = getMolecule($masterId);
$points = removeQuad(splitMolSiteWise($points));


$lam = genLAMmolFile($masterId, null, '' . '.molecule');
var_dump($points['dp']);
var_dump($lam);