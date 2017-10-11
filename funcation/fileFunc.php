<?php
/**
 * Created by PhpStorm.
 * User: Kandarp
 * Date: 4/23/2017
 * Time: 10:10 AM
 */

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
    ob_start();
    printPMData($masterId);
    $content = ob_get_contents();
    ob_end_clean();
    $actualFile = $filePath . $fileName;
    file_put_contents($actualFile, $content);
}

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