<?php
/**
 * Created by PhpStorm.
 * User: k4tru
 * Date: 11/22/2017
 * Time: 11:29 AM
 */
require_once 'database.php';
require_once 'Vec.php';
require_once 'mailer/PHPMailerAutoload.php';
require_once 'funcation/mailFunc.php';
require_once 'funcation/othFunc.php';
require_once 'funcation/fileFunc.php';
require_once 'config.php';


//get molecule points
$points = getMolecule(86);

//saperate points sitewise (prepare input arrays)
$lj = array();
$ch = array();
$dp = array();
$qd = array();
foreach ($points as $p) {
    if ($p->getSitetype() == 'LJ126') {
        array_push($lj, $p);
    } else if ($p->getSitetype() == 'Charge') {
        array_push($ch, $p);
    } else if ($p->getSitetype() == 'Dipole') {
        array_push($dp, $p);
    } else if ($p->getSitetype() == 'Quadrupole') {
        array_push($qd, $p);
    }
}


/*................................ 1. Substance.molecule file ............................*/


//output arrays
$coords = array();
$types = array();

/* 1 PART = LJ SITES */
foreach ($lj as $p) {
    $tmp = null;
    $tmp['id'] = $p->getId();
    $tmp['x'] = $p->getX();
    $tmp['y'] = $p->getY();
    $tmp['z'] = $p->getZ();
    array_push($coords, $tmp);
}
//var_dump($coords);


/* 2 PART = CHARGES */
//charges value
$CHAR[0] = 0;
foreach ($ch as $c) {
    $isSame = false;
    foreach ($lj as $l) {
        if ($c->getX() == $l->getX() && $c->getY() == $l->getY() && $c->getZ() == $l->getZ()) {
            $CHAR[$l->getId()] = $c->getOth()['Charge'];
            $isSame = true;
        }
    }
    //if charge don't have lj co ordinate
    if (!$isSame) {
        $id = $coords[sizeof($coords) - 1]['id'] + 1;
        //add to coords
        $tmp = null;
        $tmp['id'] = $id;
        $tmp['x'] = $c->getX();
        $tmp['y'] = $c->getY();
        $tmp['z'] = $c->getZ();
        array_push($coords, $tmp);
        //add to char
        $CHAR[$id] = $c->getOth()['Charge'];
    }
}


/* 3 PART = DIPOLE */
//dipole value
$DIP[0] = 0;
foreach ($dp as $d) {
    $isSame = false;
    foreach ($coords as $co) {
        if ($d->getX() == $co['x'] && $d->getY() == $co['y'] && $d->getZ() == $co['z']) {
            $DIP[$co['id']] = $d->getOth()['Dipole'];
            $isSame = true;
        }
    }
    //if charge don't have lj co ordinate
    if (!$isSame) {
        $id = $coords[sizeof($coords) - 1]['id'] + 1;
        //add to coords
        $tmp = null;
        $tmp['id'] = $id;
        $tmp['x'] = $d->getX();
        $tmp['y'] = $d->getY();
        $tmp['z'] = $d->getZ();
        array_push($coords, $tmp);
        //add to char
        $DIP[$id] = $d->getOth()['Dipole'];
    }
}
var_dump($DIP);
var_dump($CHAR);
var_dump($coords);