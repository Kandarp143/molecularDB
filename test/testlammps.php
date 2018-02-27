<?php
require '../database.php';
require_once '../funcation/fileFunc.php';
require_once '../funcation/othFunc.php';
require_once '../Vec.php';
require_once '../archiveMake.php';


$masterId = 88;
$points = getMolecule($masterId);
$points = removeQuad(splitMolSiteWise($points));

$t[1] = 1;
$t[2] = 1;
$t[3] = 2;
$t[4] = 1;
$t[5] = 3;
$t[6] = 2;
var_dump($t);
$f = $t;

for ($i = 1; $i <= sizeof($t); $i++) {
    for ($j = 1; $j < $i; $j++) {
        if ($t[$i] == $t[$j]) {
            if (array_key_exists($i, $f))
                unset($f[$i]);
        }
    }
}

var_dump($f);
$lam = genLAMmolFile(86, null, '' . '.molecule');
var_dump($lam);