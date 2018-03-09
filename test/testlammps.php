<?php
require '../database.php';
require_once '../funcation/fileFunc.php';
require_once '../funcation/othFunc.php';
require_once '../Vec.php';
require_once '../archiveMake.php';


$masterId = 86;

$points = getMolecule($masterId);
$lamArray = modifyArrForGro(genLAMmolFile($masterId, null, 'gro'));
$m = sizeof($lamArray['coords']);

var_dump($lamArray['coords']);

//req
$xMax = $lamArray['coords'][0]['x'];
$xMin = $lamArray['coords'][0]['x'];
$yMax = $lamArray['coords'][0]['y'];
$yMin = $lamArray['coords'][0]['y'];
$zMax = $lamArray['coords'][0]['z'];
$zMin = $lamArray['coords'][0]['z'];

foreach ($lamArray['coords'] as $c) {
    $xMax = max($xMax, $c['x']);
    $xMin = min($xMin, $c['x']);
    $yMax = max($yMax, $c['y']);
    $yMin = min($yMin, $c['y']);
    $zMax = max($zMax, $c['z']);
    $zMin = min($zMin, $c['z']);
}
var_dump($xMax);
var_dump($xMin);
var_dump($yMax);
var_dump($yMin);
var_dump($zMax);
var_dump($zMin);