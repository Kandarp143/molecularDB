<?php
/**
 * Created by PhpStorm.
 * User: Kandarp
 * Date: 4/23/2017
 * Time: 10:10 AM
 */

/* ------------------------------------------------------------------------------------------------- ETC  ------------*/

function getExt($fileName)
{
    $ext = substr($fileName, strpos($fileName, '.'), strlen($fileName) - 1);
    return $ext;
}

function validateSize($actual, $expected)
{

//    echo '<br/><br/>validateSize' . $actual <= $expected;
    return $actual <= $expected;
}

function clearDirectory($path)
{
    touch($path);
    $path = realpath($path);
    $files = glob($path . '/*'); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file))
            unlink($file); // delete file
    }
}

/* ------------------------------------------------------------------------------------------------- PARSE PM --------*/

function parsePMFile($file)
{
    //Read and Insert data
    $f = fopen($file, "r") or exit("Unable to open file!");
    $members = array();
    while (!feof($f)) {
        $members[] = fgets($f);
    }


    //print filearray and file data
//    var_dump($fileArray);
//    var_dump($finalData);

    fclose($f);

    return parsePMArray($members);
}

function parsePMArray($members)
{
    //trim and remove blank lines
    $trimmed_array = array_values(array_filter($members, "trim"));
    $fileArray = array_map('trim', $trimmed_array);
    //make master data array
    $count = 0;
    $finalData = array();

    foreach ($fileArray as $key => $value) {
        //remove unused lines
        if (strpos($value, "NSiteTypes") === 0 ||
            strpos($value, "NSites") === 0 ||
            strpos($value, "NRotAxes") === 0 ||
            //flexible substance
            strpos($value, "IdfType") === 0 ||
            strpos($value, "NIdfs") === 0 ||
            strpos($value, "NConstrU") === 0
        ) {
            //removing unused element
            unset($fileArray[$key]);
        }

    }
    //convert it to key value pair
    foreach ($fileArray as $key => $value) {
        $tempArray = explode("=", $value);
        $tempArray[0] = trim($tempArray[0], " \t\n\r\0\x0B\xc2\xa0");
        if (!isset($tempArray[1])) {
            $tempArray[1] = $tempArray[0];
        }
        if (array_key_exists($tempArray[0], $finalData)) {
            $count++;
            // echo "Key exists!" . $tempArray[0] . "<br>";
            $tempArray[0] = trim($tempArray[0]) . '$' . $count;
            $finalData[$tempArray[0]] = $tempArray[1];
        } else {
            $finalData[$tempArray[0]] = $tempArray[1];
            // echo "Key does not exist!<br>";
        }
    }

    return $finalData;
}

/* ------------------------------------------------------------------------------------------------- UPLOAD PM -------*/

function uploadFile($file, $path, $filename)
{
    // Check if we can upload to the specified path, if not DIE and inform the user.
    if (!is_writable($path))
        die('You cannot upload to the specified directory, ' . $path);

    // Upload the file to your specified path.
    if (move_uploaded_file($file, $path . $filename)) {
        //        echo 'Your file upload was successful'; // It worked.
    } else
        die ('There was an error during the file upload. Please try again.'); // It failed
}

function processMolDetail($masterId, $pmData, $act, $db)
{
    if ($act == 'up') {
        /* delete previous records*/
        $db->delete('DELETE FROM pm_detail WHERE master_id = ?', array($masterId));
    }

    $flexData = null;
    //check weather contains flex data
    $devider = array_search("NIdfTypes", array_keys($pmData));
    if ($devider) {
        //split array to pm data and flex data
        $flexData = array_slice($pmData, $devider + 1, sizeof($pmData));
        $pmData = array_slice($pmData, 0, $devider);
    }
    //detail records;
    foreach ($pmData as $key => $value) {
        if (strpos($key, "#") === 0 || strpos($key, "SiteType") === 0) {
            if (strpos($key, "SiteType") === 0) {
                $sitetype = $value;
            }
            if (strpos($key, "#") === 0) {
                $site = str_replace('#', '', $value);
            }
        } else {
            $param = current(explode("$", $key));
            //inserting data
            $db->insert('INSERT INTO pm_detail (master_id,site_type,site,param,val) values(?, ?, ?,?,?)', array($masterId, trim($sitetype, " "), trim($site, " "), trim($param, " "), trim($value, " ")));
        }
    }

    //flex detail
    if ($devider) {
        $field = '';
        $sites = '';
        $fieldType = '';
        $tmpKey = '';
        $tmpVal = '';
        foreach ($flexData as $key => $value) {
            $key = trim(current(explode("$", $key)));
            //master_Id
            //field
            if ($key == 'Constraint') {
                $tmpKey = $key;
                $tmpVal = $value;
                continue;
            }
            if ($key == 'Bond' || $key == 'Angle' || $key == 'Dihedral' || $key == 'ConstrU') {
                $field = $key;
                $sites = trim($value);
                $key == 'ConstrU' ? $fieldType = 'ConstrU' : $fieldType = 'IdfType';
                //explict add
                if ($key == 'ConstrU') {
//                        var_dump('INSERT : ' . $masterid . ' --  ' . $field . ' --  ' . $sites . ' --  ' . $tmpKey . ' --  ' . $tmpVal);
                    $db->insert('INSERT INTO pm_flexible (master_id,field,field_type,sites,param,val) values(?, ?,?, ?,?,?)',
                        array($masterId, $field, $fieldType, $sites, $tmpKey, trim($tmpVal, " ")));

                }
            } else {
//                    var_dump('INSERT : ' . $masterid . ' --  ' . $field . ' --  ' . $sites . ' --  ' . $key . ' --  ' . $value);
                $db->insert('INSERT INTO pm_flexible (master_id,field,field_type,sites,param,val) values(?, ?,?, ?,?,?)',
                    array($masterId, $field, $fieldType, $sites, $key, trim($value, " ")));
            }
        }
    }

}

/* ------------------------------------------------------------------------------------------------- PM FILE ---------*/
function printPMData($masterId)
{
    $type = null;
    $sitetype = null;
    $site = null;
    $NSite = null;
    $result = null;

    $db = new Database();
    //get molecule type
    $result = $db->selectRecords('select type from pm_master where master_id=?', array($masterId));
    $type = $result[0][0];
    //getting total sitetype
    $result = $db->selectRecords('SELECT COUNT(b.site_type) FROM 
(                       SELECT DISTINCT a.site_type FROM pm_detail a WHERE a.master_id= ?) b', array($masterId));
    $NSiteTypes = $result[0][0];

    //getting  total site group by sitetype
    $result = $db->selectRecords('SELECT COUNT(b.site) nsite,b.site_type FROM (SELECT DISTINCT a.site_type,a.site 
FROM pm_detail a WHERE a.master_id= ?)b GROUP BY b.site_type', array($masterId));
    //get content of Number of types
    foreach ($result as $row) {
        $NSite[$row['site_type']] = $row['nsite'];
    }
    $cout = 1;
    $result = $db->selectRecords('SELECT * FROM pm_detail WHERE master_id =?', array($masterId));
    //print content
    print  "NSiteTypes" . "  =  " . $NSiteTypes . "\n\n";
    foreach ($result as $row) {
        if ($sitetype != $row['site_type']) {
            print "\n" . "SiteType" . "   =  " . $row['site_type'] . "\n";
            print  "NSites" . "   =  " . $NSite[$row['site_type']] . "\n\n";
            $sitetype = $row['site_type'];
            $cout += 1;
        }
        if ($site != $row['site']) {
            print "\n" . "#" . $row['site'] . "\n";
            $site = $row['site'];
        }
        print  $row['param'] . "   =  " . $row['val'] . "\n";
    }
    print "\nNRotAxes   =   auto\n";

    // add flexible molecule detail
    if ($type == 'Flexible') {
        $result = $db->selectRecords('SELECT * FROM pm_flexible WHERE master_id =?', array($masterId));
        $idfType = '';
        $sites = '';
        $tmpIdfType = '';
        $tmpSites = '';
        $idfTypeCount = 0;
        $ConstrUCount = 0;
        $idfCount = null;
        $tempCount = 0;
        //        pre process data for N(field) printing
        foreach ($result as $row) {
            if ($idfType . $sites != $row['field'] . $row['sites']) {
                if ($idfType != $row['field']) {
                    $tempCount = 0;
                    $idfType = $row['field'];
                    if ($row['field_type'] == 'IdfType') {
                        $idfTypeCount += 1;
                    } else if ($row['field_type'] == 'ConstrU') {
                        $ConstrUCount += 1;
                    }
                }
                $sites = $row['sites'];
                $tempCount += 1;
                $idfCount[$row['field']] = $tempCount;
            }
        }
        //        print final data
        print "\n" . "NIdfTypes  =  " . $idfTypeCount . "\n";
        foreach ($result as $row) {
            if ($idfType . $sites != $row['field'] . $row['sites']) {
                if ($idfType != $row['field']) {
                    $idfType = $row['field'];
                    if ($row['field_type'] == 'IdfType') {
                        print "\n" . "IdfType  =  " . $idfType . "\n";
                        print  "NIdfs  =  " . $idfCount[$row['field']] . "\n";
                    } else if ($row['field_type'] == 'ConstrU') {
                        print "\n" . "NConstrU  =  " . $idfCount[$row['field']] . "\n";
                    }
                }
                $sites = $row['sites'];
                if ($idfType != 'ConstrU') {
                    print "\n" . $idfType . "  =  " . $sites . "\n";
                } else {
                    print "\n";
                    $tmpIdfType = $idfType;
                    $tmpSites = $sites;
                }
            }
            print $row['param'] . "  =  " . $row['val'] . "\n";
            if ($row['param'] == "Constraint") {
                print $tmpIdfType . "  =  " . $tmpSites . "\n";
            }
            if ($row['field_type'] == 'ConstrU') {
                print "NRotAxes   =   auto\n";
            }
        }
        print "\n" . "NRotAxes   =   auto\n";
    }
}

function genPMFile($masterId, $filePath, $fileName)
{
    //generating file
    ob_start();
    printPMData($masterId);
    $content = ob_get_contents();
    ob_end_clean();
    $actualFile = $filePath . $fileName;
    file_put_contents($actualFile, $content);
}

/* ------------------------------------------------------------------------------------------------- LS FILE ---------*/
function genLsFile($masterId, $filePath, $fileName)
{

    $today = date("dmY");

    /*Getting Data*/
    $db = new Database();
    $result = $db->selectRecords('SELECT * FROM pm_detail WHERE master_id =?', array($masterId));

    /*file to be generate*/
    $actualFile = $filePath . $fileName;

    /* needed for latest php */
    touch($actualFile);
    $actualFile = realpath($actualFile);

    /*create a new xmlwriter object*/
    $xml = new XMLWriter();
    //Define File loc
    $xml->openURI($actualFile);
    //using memory for string output
    //$xml->openMemory();
    //set the indentation to true (if false all the xml will be written on one line)
    $xml->setIndent(true);
    //create the document tag, you can specify the version and encoding here
    $xml->startDocument('1.0', 'UTF-8');
    //Create an element
    $xml->startElement("components");
    $xml->writeAttribute('version', $today);
    $xml->startElement("moleculetype");
    $xml->writeAttribute('id', 1);
    $xml->writeAttribute('name', substr($fileName, 0, strrpos($fileName, ".")));
    $c = 0;
    $quadParam[] = null;
    foreach ($result as $row) {
        if (trim($row['param']) == 'x') {
            $c += 1;
            if ($c > 1) {
                $xml->endElement();
            }
            $xml->startElement("site");
            $xml->writeAttribute('type', $row['site_type']);
            $xml->writeAttribute('id', $c);
            $xml->startElement("coords");
            $xml->setIndent(false);
            $xml->writeElement($row['param'], $row['val']);
        } else if (trim($row['param']) == 'y') {
            $xml->writeElement($row['param'], $row['val']);
        } else if (trim($row['param']) == 'z') {
            $xml->writeElement($row['param'], $row['val']);
            $xml->setIndent(true);
            $xml->endElement(); //end cord
            //if Quadrupole create new tag
            if ($row['site_type'] == 'Quadrupole') {
                //start quadrupolemoment
                $xml->startElement("quadrupolemoment");
                $xml->setIndent(false);
            }
        } else {
            if ($row['param'] != 'shielding') {
                if ($row['site_type'] == 'Quadrupole') {
                    if ($row['param'] == 'theta' || $row['param'] == 'phi' || $row['param'] == 'quadrupole') {
                        if ($row['param'] == 'quadrupole') {
                            $xml->writeElement('abs', $row['val']);
                        } else {
                            $xml->writeElement($row['param'], $row['val']);
                        }
                    } else {
                        $quadParam[$row['param']] = $row['val'];
                    }
                } else {
                    $xml->writeElement($row['param'], $row['val']);
                }
            }
        }
    }
    if ($row['site_type'] == 'Quadrupole') {
        $xml->setIndent(true);
        $xml->endElement(); //end quadrupolemoment
        if (!empty($quadParam))
            foreach ($quadParam as $key => $val) {
                if ($key != 0 || !empty($val))
                    $xml->writeElement($key, $val);
            }
    }
    $xml->endElement(); //end last site

    $xml->startElement("momentsofinertia");
    $xml->writeAttribute('rotaxes', 'xyz');
    $xml->writeElement('Ixx', '0');
    $xml->writeElement('Iyy', '0');
    $xml->writeElement('Izz', '0');
    $xml->endElement(); //End momentsofinertia
    $xml->endElement(); //End moleculetype
    $xml->endElement(); //End components
    $xml->endDocument();
    $xml->flush();
}

/* ------------------------------------------------------------------------------------------------- LAMPPS (MOL)-----*/
function genLAMmolFile($masterId, $filePath, $fileName)
{
    /*this method will generate all arrays for lampps*/


    //declaring output
    $coords = array();
    $types = array();
    //supporting array
    $CHAR[0] = 0;
    $DIP[0] = 0;
    $DIPMAP = null;
    $SIG = null;
    $EPS = null;
    $MASS = null;

    //getting require data
    $points = getMolecule($masterId);
    //prepare array for file
    if ($fileName == 'gro') {
        //if this array will use in gromacs - convert quad and dipole
        $molecule = removeQuad(removeDipole(splitMolSiteWise($points)));
    } else {
        //if this array only for lamps- convert quad
        $molecule = removeQuad(splitMolSiteWise($points));
    }
    $pmatrix = splitPmatrixSiteWise(makeZmatrix($points, 0)['pmatrix']);

    /*********************************************** COORDS ***********************/
    /* 1 PART = LJ SITES */
    foreach ($molecule['lj'] as $p) {
        $tmp = null;
        $tmp['id'] = $p->getId();
        $tmp['x'] = $p->getX();
        $tmp['y'] = $p->getY();
        $tmp['z'] = $p->getZ();
        array_push($coords, $tmp);
    }
    /* 2 PART = CHARGES */
    foreach ($molecule['ch'] as $c) {
        $isSame = false;
        foreach ($molecule['lj'] as $l) {
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
    foreach ($molecule['dp'] as $d) {
        $isSame = false;
        foreach ($coords as $co) {
            if ($d->getX() == $co['x'] && $d->getY() == $co['y'] && $d->getZ() == $co['z']) {
                $DIP[$co['id']] = $d->getOth()['Dipole'];
                $DIPMAP[$co['id']] = $d->getOth();
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
            $DIPMAP[$id] = $d->getOth();
        }
    }


    /***************************************** DCLARING M  ***********************/
    $m = sizeof($coords);
    //extra tune and fine
    for ($i = 0; $i <= $m; $i++) {
        //update dimenison
        if (!array_key_exists($i, $CHAR))
            $CHAR[$i] = 0;
        if (!array_key_exists($i, $DIP))
            $DIP[$i] = 0;
        //static dimenison
        $SIG[$i] = 0;
        $EPS[$i] = 0;
    }
    //sorting all
    ksort($CHAR);
    ksort($DIP);


    /*********************************************** TYPES ***********************/


    //SIG and EPS
    foreach ($pmatrix['lj'] as $p) {
        $SIG[$p['Site']] = $p['Sigma'];
        $EPS[$p['Site']] = $p['Epsilon'] * 0.0000861733035;
    }
    ksort($SIG);
    ksort($EPS);

    //TYPE array column wise (column1 = key , column2 = value;)
    // column1
    for ($i = 1; $i <= $m; $i++) {
        $types[$i] = 0;
    }
    //column2
    $types[1] = 1;
    for ($i = 2; $i <= $m; $i++) {
        $isSame = false;
        for ($j = 0; $j < $i; $j++) {
            if ($CHAR[$i] == $CHAR[$j] && $DIP[$i] == $DIP[$j] && $SIG[$i] == $SIG[$j] && $EPS[$i] == $EPS[$j]) {
                $types[$i] = $types[$j];
                $isSame = true;
            }
        }
        if (!$isSame) {
            $types[$i] = $types[$i - 1] + 1;
        }
    }
//    removing duplicates from types
    end($types);         // move the internal pointer to the end of the array
    $lastKey = key($types);
    $tmpTypes = $types;
    for ($i = 1; $i <= $lastKey; $i++) {
        for ($j = 1; $j < $i; $j++) {
            if (array_key_exists($i, $types) && array_key_exists($j, $types))
                if ($types[$i] == $types[$j])
                    if (array_key_exists($i, $tmpTypes))
                        unset($tmpTypes[$i]);
        }
    }

    //mass
    //declaring mass
    for ($i = 0; $i < sizeof($coords); $i++) {
        $MASS[$coords[$i]['id']] = 0.000001;
        /* updateing mas */
        foreach ($molecule['lj'] as $lj) {
            if ($coords[$i]['x'] == $lj->getX() && $coords[$i]['y'] == $lj->getY() && $coords[$i]['z'] == $lj->getZ()) {
                $MASS[$coords[$i]['id']] = $MASS[$coords[$i]['id']] + $lj->getOth()['Mass'];
            }
        }
        foreach ($molecule['ch'] as $lj) {
            if ($coords[$i]['x'] == $lj->getX() && $coords[$i]['y'] == $lj->getY() && $coords[$i]['z'] == $lj->getZ()) {
                $MASS[$coords[$i]['id']] = $MASS[$coords[$i]['id']] + $lj->getOth()['Mass'];
            }
        }
        foreach ($molecule['dp'] as $lj) {
            if ($coords[$i]['x'] == $lj->getX() && $coords[$i]['y'] == $lj->getY() && $coords[$i]['z'] == $lj->getZ()) {
                $MASS[$coords[$i]['id']] = $MASS[$coords[$i]['id']] + $lj->getOth()['Mass'];
            }
        }

        //check.
        if ($MASS[$coords[$i]['id']] != 0.000001) {
            $MASS[$coords[$i]['id']] = $MASS[$coords[$i]['id']] - 0.000001;
        }
    }

    // printing output
    $LAMmolData['name'] = strtok($fileName, '.');
    $LAMmolData['coords'] = $coords;
    $LAMmolData['types'] = $types;
    $LAMmolData['tmpTypes'] = $tmpTypes;
    $LAMmolData['CHAR'] = $CHAR;
    $LAMmolData['DIP'] = $DIP;
    $LAMmolData['DIPMAP'] = $DIPMAP;
    $LAMmolData['SIG'] = $SIG;
    $LAMmolData['EPS'] = $EPS;
    $LAMmolData['MASS'] = $MASS;

    //if only array required
    if ($fileName != null && $filePath != null) {
        /*********************************************** GENERATE FILE ***********************/
        ob_start();
        printLAMmolData($LAMmolData);
        $content = ob_get_contents();
        ob_end_clean();
        $actualFile = $filePath . $fileName;
        file_put_contents($actualFile, $content);
    }
    //returning output
    return $LAMmolData;
}

function printLAMmolData($lam)
{
    //heading
    print  "#" . $lam["name"] . " Model Bolzmann-Zuse Society \n\n";

    //stats
    print  sizeof($lam['coords']) . " atoms \n";
    print  "0" . " bonds \n";
    print  "0" . " angles \n";
    print  "0" . " dihedrals \n\n\n";

    //coords
    print  "Coords \n";
    foreach ($lam['coords'] as $coord) {
        print "\n";
        foreach ($coord as $c) {
            print $c . "  ";
        }
    }

    //types
    print  "\n\n\nTypes \n\n";
    foreach ($lam['types'] as $key => $value) {
        print $key . "  " . $value . "\n";
    }

}

/* ------------------------------------------------------------------------------------------------- LAMPPS (INT)-----*/
function genLAMintFile($masterId, $filePath, $fileName, $LAMmolData)
{
    //generating file
    ob_start();
    printLAMintData($LAMmolData, $masterId);
    $content = ob_get_contents();
    ob_end_clean();
    $actualFile = $filePath . $fileName;
    file_put_contents($actualFile, $content);
}

function printLAMintData($lam, $masterId)
{
    //get modified types with remove duplicates
    $types = $lam['tmpTypes'];

    print "# Set interaction parameters between particles \n\n";

    print "pair_style         lj/long/dipole/long off off $$ \n";
    print "pair_modify        mix arithmetic \n\n";


    print "#int\n";
    foreach ($types as $key => $val) {
        print "pair_coeff      ";
        if ($lam['EPS'][$key] == 0 && $lam['SIG'][$key] == 0) {
            print $val . "  " . "*" . " 0.0  0.0 \n";
        } else {
            print $val . "  " . $val . " " . $lam['EPS'][$key] . "  " . $lam['SIG'][$key] . "\n";
        }


    }

    $isCharge = false;
    $isDipole = false;
    //check weather going ti display or not
    foreach ($types as $key => $val) {
        if ($lam['CHAR'][$key] != 0)
            $isCharge = true;
        if ($lam['DIP'][$key] != 0)
            $isDipole = true;
    }

    if ($isCharge) print "\n\n#charge\n";
    foreach ($types as $key => $val) {
        if ($lam['CHAR'][$key] != 0) {
            print "set type  " . $val . " charge  " . $lam['CHAR'][$key] . " \n";
        }
    }

    if ($isDipole) print "\n\n#dipole\n";
    foreach ($types as $key => $val) {
        if ($lam['DIP'][$key] != 0) {
            print "set type  " . $val . " dipole  " .
                $lam['DIP'][$key] * sin($lam['DIPMAP'][$key]['Theta']) * cos($lam['DIPMAP'][$key]['Phi']) . "  " .
                $lam['DIP'][$key] * sin($lam['DIPMAP'][$key]['Theta']) * sin($lam['DIPMAP'][$key]['Phi']) . "  " .
                $lam['DIP'][$key] * cos($lam['DIPMAP'][$key]['Theta']) . " \n";
        }
    }

    print "\n\n#mass\n";
    //printing mass

//    foreach ($lam['MASS'] as $key => $val) {
//        print "mass " . $key . " " . $val . " \n";
//    }

    foreach ($lam['tmpTypes'] as $key => $val) {
        print "mass " . $val . " " . $lam['MASS'][$key] . " \n";
    }
}

/* ------------------------------------------------------------------------------------------------- GROMACS (FF.ITP)-*/
function genGROffitpFile($filePath, $fileName)
{
    //generating file
    ob_start();

    //printing data (static)
    print "[ defaults ]\n";
    print "; nbfunc    comb-rule gen-pairs\n";
    print "    1         2           no\n";


    $content = ob_get_contents();
    ob_end_clean();
    $actualFile = $filePath . $fileName;
    file_put_contents($actualFile, $content);
}


/* ------------------------------------------------------------------------------------------------- GROMACS (MOL.ITP)*/
function genGROmolitpFile($masterId, $filePath, $fileName)
{
    //generating file
    ob_start();

    printGROmolitpData($masterId, $fileName);
    $content = ob_get_contents();
    ob_end_clean();
    $actualFile = $filePath . $fileName;
    file_put_contents($actualFile, $content);
}

function printGROmolitpData($masterId, $fileName)
{
    //raw data
    $lamArray = modifyArrForGro(genLAMmolFile($masterId, null, "gro"));
    $m = sizeof($lamArray["coords"]);
    //get subdtance name from file name
//    $substance = str_replace(".itp", "", $fileName);
    $substance = substr($fileName, 0, strpos($fileName, "."));
    $substance = substr($substance, 0, strpos($substance, "_"));

    /* SECTION 1 */
    print "; rigid model: use the option \"all-angles\" *.mdp file \n";
    print "[ atomtypes ]\n";
    print "; " .
        "name    " .
        "bond_type    " .
        "mass         " .
        "charge       " .
        "ptype    " .
        "sigma (nm)       " .
        "epsilon (kJ/mol)\n";

    for ($i = 1; $i <= $m; $i++) {
        print "  " .
            "A" . $i . "       " .
            "A" . $i . "          " .
            str_pad($lamArray["MASS"][$i], 6, "0", STR_PAD_RIGHT) . "      " .
            str_pad($lamArray["CHAR"][$i], 6, "0", STR_PAD_LEFT) . "       " .
            "A        " .
            str_pad($lamArray["SIG"][$i], 6, "0", STR_PAD_LEFT) . "         " .
            str_pad($lamArray["EPS"][$i], 6, "0", STR_PAD_LEFT) . "\n";
    }

    print "\n\n\n";

    /* SECTION 2 */
//make points for create Zmatrix
//declaring variables
    $points = array();
    $point = null;
    $oth = null;
    foreach ($lamArray["coords"] as $row) {
        $point = new Vec();
        $point->setId($row["id"]);
        $point->setName($row["id"]);
        $point->setSitetype($row["id"]);
        $point->setX($row["x"]);
        $point->setY($row["y"]);
        $point->setZ($row["z"]);
        $point->setOth($oth);
        array_push($points, $point);
    }
    $returnArray = makeZmatrix($points, true);
    $zmatrix = $returnArray["zmatrix"];
    $dis = array();
    $ang = array();;
    $dih = array();;
    foreach ($zmatrix as $z) {
        $z[4] != "-" ? $dis[sizeof($dis) + 1] = $z[4] : "";
        $z[6] != "-" ? $ang[sizeof($ang) + 1] = $z[6] : "";
        $z[8] != "-" ? $dih[sizeof($dih) + 1] = $z[8] : "";
    }

    if ($m >= 3) {
        print "[ angletypes ]\n";
        print ";     " .
            "i        " .
            "j       " .
            "k       " .
            "func   " .
            "th0         " .
            "cth\n";

        for ($i = 1; $i < $m - 1; $i++) {
            print "      " .
                "A" . $i . "       " .
                "A" . ($i + 1) . "      " .
                "A" . ($i + 2) . "      " .
                "1      " .
                $ang[$i] . "      " .
                "1000.000    \n";
        }
    }


    print "\n\n\n";
    /* SECTION 3 */

    print "[ moleculetype ]\n";
    print $substance .
        "             " .
        ($m - 1);

    print "\n\n\n";
    /* SECTION 4 */

    print "[ atoms ]\n";
    print ";    " .
        "nr   " .
        "type   " .
        "resnr   " .
        "residue   " .
        "atom   " .
        "cgnr   " .
        "charge       " .
        "mass   \n";


    for ($i = 1; $i <= $m; $i++) {
        print "     " .
            $i . "    " .
            "A" . $i . "     " .
            "1       " .
            "MOD       " .
            "A" . $i . "     " .
            "1      " .
            $lamArray["CHAR"][$i] . "     " .
            str_pad($lamArray["MASS"][$i], 6, "0", STR_PAD_RIGHT) . "    \n";
    }

    print "\n\n\n";
    /* SECTION 5 */

    print "[ constraints ]\n";
    print ";     " .
        "fixed   " .
        "bond   " .
        "length    \n";

    print ";      " .
        "          " .
        "       " .
        "d (nm)    \n";

    if ($m > 1) {
        for ($i = 1; $i < $m; $i++) {
            print "      " .
                $i . "       " .
                ($i + 1) . "      " .
                "1  " .
                ($dis[$i] / 10) . "    \n";
        }


        print "\n\n\n";
        /* SECTION 6 */

        print "[ angles ]\n";
        print "; use the option \"all - angles\" *.mdp file\n";
        for ($i = 1; $i < $m - 1; $i++) {
            print "    " .
                $i . "     " .
                ($i + 1) . "     " .
                ($i + 2) . "       " .
                "1    \n";
        }


    }

    if ($m > 3) {
        print "\n\n\n";
        /* SECTION 7 */
        print "[ dihedral_restraints ]\n";
        print "; ai aj ak al type label phi dphi kfac power\n";
        for ($i = 1; $i < $m - 2; $i++) {
            print "     " .
                $i . "      " .
                ($i + 1) . "        " .
                ($i + 2) . "        " .
                ($i + 3) . "        " .
                "1        " .
                "1        " .
                $dih[$i] . "        " .
                "0        " .
                "1        " .
                "2    \n";
        }

    }

    print "\n\n\n";

    /* SECTION 8 */
//preparing pattern array
    $mA = array();
    for ($i = 1; $i <= $m; $i++) {
        $mA[] = $i;
    }
    $exc = array();
    $mB = $mA;
    array_push($exc, $mA);
    $con = true;
    while ($con) {
        $mA = $mB;
        foreach ($mA as $key => $value) {
            $key == 0 ? $mB[$key] = $mA[sizeof($mA) - 1] : $mB[$key] = $mA[$key - 1];
        }
        if ($exc[0] == $mB) {
            $con = false;
        } else {
            array_push($exc, $mB);
        }
    }

    print "[ exclusions ]\n";
    foreach ($exc as $row) {
        foreach ($row as $e) {
            print " " . $e . "  ";
        }
        print "\n";
    }
}

/* ------------------------------------------------------------------------------------------------- GROMACS (ATP)----*/
function genGROatpFile($masterId, $filePath, $fileName)
{
    //generating file
    ob_start();

    //printing data (static)
    $lamArray = modifyArrForGro(genLAMmolFile($masterId, null, 'gro'));
    $m = sizeof($lamArray['coords']);
    for ($i = 1; $i <= $m; $i++) {
        print "A" . $i . "        " . $lamArray['MASS'][$i] . "\n";
    }

    $content = ob_get_contents();
    ob_end_clean();
    $actualFile = $filePath . $fileName;
    file_put_contents($actualFile, $content);
}

/* ------------------------------------------------------------------------------------------------- GROMACS (PDB)----*/

function genGROpdbFile($masterId, $filePath, $fileName)
{
    //generating file
    ob_start();
    printGROpdbData($masterId);
    $content = ob_get_contents();
    ob_end_clean();
    $actualFile = $filePath . $fileName;
    file_put_contents($actualFile, $content);
}

function printGROpdbData($masterId)
{
    $lamArray = modifyArrForGro(genLAMmolFile($masterId, null, 'gro'));
    $m = sizeof($lamArray['coords']);

    print "MODEL 1\n";
    print "CRYST1 1.8 0.9 1.0 90.00 90.00 90.00\n";

//req
    $xMax = $lamArray['coords'][0]['x'];
    $xMin = $lamArray['coords'][0]['x'];
    $yMax = $lamArray['coords'][0]['y'];
    $yMin = $lamArray['coords'][0]['y'];
    $zMax = $lamArray['coords'][0]['z'];
    $zMin = $lamArray['coords'][0]['z'];

    foreach ($lamArray['coords'] as $c) {
        $xMax = max($xMax, $c['x']);
        $xMin = max($xMin, $c['x']);
        $yMax = max($yMax, $c['y']);
        $yMin = max($yMin, $c['y']);
        $zMax = max($zMax, $c['z']);
        $zMin = max($zMin, $c['z']);
    }

    foreach ($lamArray['coords'] as $c) {


        print "ATOM     " .
            $c['id'] . "    " .
            "A" . $c['id'] . "    " .
            "MOD    " .
            "1    " .
            "0.000    " .
            "0.000    " .
            $c['x'] . "    " .
            $c['y'] . "    " .
            $c['z'] . "     \n";
    }
}