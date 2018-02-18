<?php
/**
 * Created by PhpStorm.
 * User: Kandarp
 * Date: 4/26/2017
 * Time: 4:37 PM
 */


/*func to  format the site name by custom rule. */
function toSubstanceTitle($substance)
{
    //1.if {} - remove and change font
    //2.if start with R next number should be upstring
    //3.if () should be in upstring
    //4.if (+,-) one number before sign should be upstring
    //5.all remain string up and number substring
//    $substance2 = $substance;

    $substance = str_replace('{', '<span style="font-family: myFirstFont">', $substance);
    $substance = str_replace('}', ' </span>', $substance);
    $left = '';
    $mid = '';
    $right = '';


    //if string start with R
    if (0 === strpos($substance, 'R')) {
        //getting next char with index
        preg_match('~[a-z]~i', substr($substance, 1), $match, PREG_OFFSET_CAPTURE);

        $left = substr($substance, 0, $match[0][1] + 1);
        $substance = substr($substance, $match[0][1] + 1);
    }

    //if string end with ()
    if (strlen($substance) - 1 === strpos($substance, ')')) {
        //getting next char with index
        $pos = strpos($substance, '(');
        $right = substr($substance, $pos);
        $substance = substr($substance, 0, $pos);
        //removeing spaces
        $substance = trim($substance);
    }

    //still if string end with (+ or -)
    if (strlen($substance) - 1 === strpos($substance, '+') || strlen($substance) - 1 === strpos($substance, '-')) {
        //getting one char before + or -
        $mid = substr($substance, strlen($substance) - 2);
        $substance = substr($substance, 0, strlen($substance) - 2);

    }
    //make numaric subscript
    $substance = $left . preg_replace('/[0-9]+/', '<sub>$0</sub>', $substance) . $mid . $right;


//    return trim($substance2 . ' = ' . $substance);
    return $substance;
}

/*func to find if the site has ion (+.-) to display different in database */
function isSubstanceIonic($substance)
{

    if (strlen($substance) - 1 === strpos($substance, '+') || strlen($substance) - 1 === strpos($substance, '-'))
        return true;
    return false;

}

/*func to  format site type by custom role*/
function toFormatSiteType($siteType)
{
    if ($siteType === 'LJ126') {
        $siteType = 'Lennard-Jones 12-6';
    } else {
        $siteType = "<br/>" . $siteType;
    }

    return $siteType;
}

/*func to get formatted reference for given substance */
function referenceMessage($masterId)
{
    $db = new Database();
    $refs = $db->selectRecords('SELECT DISTINCT pm_bib.bib_type,pm_bib.bib_title,pm_bib.param,pm_bib.value 
FROM pm_bib INNER JOIN pm_master on pm_master.bibtex_ref_key=pm_bib.bib_key 
WHERE pm_master.master_id =?', array($masterId));
    return referenceMessageMsg($refs);
}

function referenceMessageMsg($refs)
{
    $tit = '-';
    $Author = '-';
    $bib_title = '-';
    $Journal = '-';
    $Volume = '-';
    $Number = '-';
    $Pages = '-';
    $Year = '-';
    $url = '-';
    $doi = '-';


    if (!empty($refs)) {
        foreach ($refs as $row) {
            if ($row['param'] == 'Author') {
                $Author = $row['value'];
            } else if ($row['param'] == 'Journal') {
                $Journal = $row['value'];
            } else if ($row['param'] == 'Volume') {
                $Volume = $row['value'];
            } else if ($row['param'] == 'Number') {
                $Number = $row['value'];
            } else if ($row['param'] == 'Pages') {
                $Pages = $row['value'];
            } else if ($row['param'] == 'Year') {
                $Year = $row['value'];
            } else if ($row['param'] == 'Title') {
                $bib_title = $row['value'];
            } else if ($row['param'] == 'Doi') {
                $doi = $row['value'];
            } else if ($row['param'] == 'Url') {
                $url = $row['value'];
            }
            $tit = $row['bib_title'];
        }

        //making final string
        $tit == '-' ? $tit = '' : $tit = '[' . $tit . ']  ';
        $Author == '-' ? $Author = '' : $Author = formatAuthor($Author) . ': ';
        $bib_title == '-' ? $bib_title = '' : $bib_title = $bib_title . ', ';
        $Journal == '-' ? $Journal = '' : $Journal = $Journal . ' ';
        $Volume == '-' ? $Volume = '' : $Volume = $Volume . ', ';
        $Number == '-' ? $Number = '' : $Number = $Number . ', ';
        $Pages == '-' ? $Pages = '' : $Pages = $Pages . ' ';
        $Year == '-' ? $Year = '' : $Year = '(' . $Year . '), ';
        $doi == '-' ? $doi = '' : $doi = '<a href="' . $url . '" target="_blank" >' . $doi . '</a>.';


        return $tit . $Author . $bib_title . $Journal . $Volume . $Number . $Pages . $Year . $doi;
    } else {
        return 'No reference found !';
    }
}

function formatAuthor($Author)

{
    //remove 'and' and make string to lowercase
    $Author = strtolower(str_replace("and", "", $Author));

    //devided into each name using endpoint(.)
    $auth = explode(".", $Author);

    //remove empty elements
    $auth = array_filter($auth);
    //trim all elements
    $auth = array_map('trim', $auth);
    //first cap of each word for each elements
    $auth = array_map('ucwords', $auth);

    $ans = '';
    $i = 0;
    $saperator = '.; ';
    $len = count($auth);
    foreach ($auth as $a) {
        if ($i == $len - 2) {
            // second last
            $saperator = '. and ';
        } else if ($i == $len - 1) {
            //last
            $saperator = '.';
        }
        $ans = $ans . $a . $saperator;
        $i++;
    }
    return $ans;
}

function referenceParameter($refs, $parameter)
{
    $ans = '';
    if (!empty($refs)) {
        foreach ($refs as $row) {
            if ($parameter == 'bib_key' || $parameter == 'bib_title') {
                $ans = $row[$parameter];
            } else {
                if ($row['param'] == $parameter) {
                    $ans = $row['value'];
                }
            }
        }

        /*extract year*/
        if (preg_match('/(\d{4})/', $ans, $matches)) {
            $year = $matches[0];
            return $year;
        } else {
            return $ans;
        }

    } else {
        return 'No reference found !';
    }
}

function referenceMessageKey($ref_id)
{
    $db = new Database();
    $refs = $db->selectRecords('SELECT DISTINCT * FROM pm_bib WHERE  pm_bib.bib_key =?', array($ref_id));
    return referenceMessageMsg($refs);
}

function referenceList()
{
    $db = new Database();
    $refs = $db->selectRecords('SELECT DISTINCT pm_bib.bib_key,pm_bib.bib_type,pm_bib.bib_title,pm_bib.param,pm_bib.value 
FROM pm_bib ORDER BY pm_bib.bib_key', null);

    $master_r = array();
    $new_r = array();
    $temp_id = 0;
    $i = 0;
    $numItems = count($refs);
    foreach ($refs as $r) {
        $i++;
        if ($r['bib_key'] != $temp_id) {
            array_push($master_r, $new_r);
            $new_r = array();
            $temp_id = $r['bib_key'];
        }
        array_push($new_r, $r);
        if ($i === $numItems) {
            //last loop
            array_push($master_r, $new_r);
        }
    }
    return $master_r;
}

/*func to generate Z-Matrix (Detail Page)  of molecule */
function makeZmatrix($points, $disp_sh)
{
    $retrunArray = array();
    //fine and tune
    foreach ($points as $p) {
        //assign tmp var
        $tmp = $p->getOth();
        //remove schelding
        if ($disp_sh == 0 && array_key_exists('Shielding', $tmp)) {
            unset($tmp['Shielding']);
        }
        $p->setOth($tmp);
        $p->setIsSame($points);
    }

    //prepareing display array
    $zmatrix = array();
    $pmatrix = array();
    $markerId = 0;

    $caller = new Vec();
    for ($i = 0; $i <= sizeof($points) - 1; $i++) {
        /*points*/
        $p1 = '-';
        $p2 = '-';
        $p3 = '-';
        $p4 = '-';
        /*reference*/
        $r1 = '-';
        $r2 = '-';
        $r3 = '-';
        $sign = 1;

        //init variables
        $p1 = isset($points[$i]) ? $points[$i] : '-';
        if ($i > 0) {
            //2nd point and distance (i=1)
            $r1 = $i;
            $p2 = $points[$i - 1];
        }
        if ($i > 1) {
            //3rd point and angle (i=2)
            $r2 = $i - 1;
            $p3 = $points[$i - 2];
        }
        if ($i > 2) {
            //4th point and dihiedral (i=3)
            $r3 = $i - 2;
            $p4 = $points[$i - 3];
            /*same reference check*/
            $rPoints = $caller->updateSamePoints(array($p2, $p3, $p4, $p1), $points);
            if (!empty($rPoints)) {
                $p3 = $rPoints[1];
                $key = array_search($p3, $points);
                $r2 = $key + 1;
                $p4 = $rPoints[2];
                $key = array_search($p4, $points);
                $r3 = $key + 1;
            }
            $sign = $caller->getAngleSign($p4, $p3, $p2, $p1, $sign);
        }

        $markerId += 1;
        /*if two points are lies on same co-ordinates*/
        if ($points[$i]->getIsSame()) {
            array_push($zmatrix, array($points[$i]->getSitetype(), $markerId, $points[$i]->getName(), $points[$i]->getRef(), 0, '-', '-', '-', '-', $i + 1));
        } else {
            array_push($zmatrix,
                array(
                    $points[$i]->getSitetype(),                                                                 /*Site-Type*/
                    $markerId,                                                                                  /*Site-ID*/
                    $points[$i]->getName(),                                                                     /*Site-name*/
                    $i > 0 ? $r1 : '-',                                                                         /*Ref.*/
                    $i > 0 ? round($caller->getDistance($p2, $p1), 4) : '-',                           /*Distance */
                    $i > 1 ? $r2 : '-',                                                                         /*Ref.*/
                    $i > 1 ? round($caller->getAngle($p3, $p2, $p1), 4) * $sign : '-',                 /*Angle*/
                    $i > 2 ? $r3 : '-',                                                                          /*Ref.*/
                    $i > 2 ? round($caller->getDihedral($p4, $p3, $p2, $p1), 4) : '-',                 /*Dihedral */
                    $i + 1
                ));

        }

        /*if site type is following then need to add new point*/
        if ($points[$i]->getSitetype() == 'Dipole' || $points[$i]->getSitetype() == 'Quadrupole') {
            /* ref point*/
            $p2 = $points[$i];
            /* new point*/
            $p1 = new Vec();
            $p1->setCordinatefromVec($p2);
            $p1->setName('dir.');
//            echo 'I' . $i . 'Dir' . '</br>';
            if ($i > 0) {
                $r1 = $i + 1;
            }
            if ($i > 1) {
                $r2 = $i;
                $p3 = $points[$i - 1];
            }
            if ($i > 2) {
                $r3 = $i - 1;
                $p4 = $points[$i - 2];
                /*same reference check*/
                $rPoints = $caller->updateSamePoints(array($p2, $p3, $p4, $p1), $points);
                if (!empty($rPoints)) {
                    $p3 = $rPoints[1];
                    $key = array_search($p3, $points);
                    $r2 = $key + 1;
//                    echo 'Before :' . $p4->getName();
                    $p4 = $rPoints[2];
//                    echo ' After :' . $p4->getName();
                    $key = array_search($p4, $points);
                    $r3 = $key + 1;

                }
                $sign = $caller->getAngleSign($p4, $p3, $p2, $p1, $sign);
            }

            $markerId += 1;
            array_push($zmatrix,
                array(
                    $points[$i]->getSitetype(),                                                            /*Site-Type*/
                    $markerId,                                                                              /*Site-ID*/
                    $p1->getName(),                                                                         /*Site-name*/
                    $i > 0 ? $r1 : '-',                                                                  /*Ref.*/
                    $i > 0 ? round($caller->getDistance($p2, $p1), 4) : '-',                        /*Distance */
                    $i > 1 ? $r2 : '-',                                                                      /*Ref.*/
                    $i > 1 ? round($caller->getAngle($p3, $p2, $p1) * $sign, 4) : '-',          /*Angle*/
                    $i > 2 ? $r3 : '-',                                                                  /*Ref.*/
                    $i > 2 ? round($caller->getDihedral($p4, $p3, $p2, $p1), 4) : '-',                      /*Dihedral */
                    null
                ));
        }


        //p matrix
        if (!empty($points[$i]->getOth())) {
            $tempOth = $points[$i]->getOth();
            if (array_key_exists('Phi', $tempOth)) {
                unset($tempOth['Phi']);
            }
            if (array_key_exists('Theta', $tempOth)) {
                unset($tempOth['Theta']);
            }
            array_push($pmatrix, array($points[$i]->getSitetype(), $i + 1, $tempOth));
        }

    }
    $temp = '';
    $mk = null;
    $mk2 = null;
    for ($i = 0; $i <= sizeof($zmatrix) - 1; $i++) {
        $siteType = $zmatrix[$i][0];
        $zId = $zmatrix[$i][1];
        $pId = empty($zmatrix[$i][9]) ? $zmatrix[$i - 1][9] : $zmatrix[$i][9];
        if ($temp !== $zmatrix[$i][0] && $pId !== 0) {
            $mk[] = $zId;
            $mk2[] = $pId;
            $temp = $siteType;
        }
        if ($i == sizeof($zmatrix) - 1) {
            //last
            $mk[] = $zId;
            $mk2[] = $pId;
        }
    }

    //$maker1 array for making dynamic rowspan and bracket
    $maker = null;
    for ($i = 0; $i <= sizeof($mk) - 2; $i++) {
        $key = $mk[$i];
        $val = $mk[$i + 1] - $mk[$i];
        if ($i == sizeof($mk) - 2) {
            $val += 1;
        }
        $maker[$key] = $val;
    }
    $maker2 = null;
    for ($i = 0; $i <= sizeof($mk2) - 2; $i++) {
        $key = $mk2[$i];
        $val = $mk2[$i + 1] - $mk2[$i];
        if ($i == sizeof($mk2) - 2) {
            $val += 1;
        }
        $maker2[$key] = $val;
    }
//    echo '<pre>';
//    echo var_dump($maker2);
//    echo '</pre>';

    $retrunArray['pmatrix'] = $pmatrix;
    $retrunArray['zmatrix'] = $zmatrix;
    $retrunArray['maker'] = $maker;
    $retrunArray['maker2'] = $maker2;

    return $retrunArray;
}

function makeFlexArray($masterId)
{
    $bondArray = array();
    $angleArray = array();
    $diheidralArray = array();
    $constrArray = array();

    $db = new Database();
    /*prepare all  tables : bond , angle , dihedrial , coonstrain*/
    $result = $db->selectRecords('SELECT * FROM pm_flexible WHERE master_id = ?', array($masterId));
    $bond1 = '';
    $bond2 = '';
    $angle1 = '';
    $angle2 = '';
    $constr1 = 1;
    $constr2 = 0;
    $count = 0;
    $siteId = 0;
    $field = 0;
    $temp_array = array();
    $numItems = count($result);
    foreach ($result as $row) {
        if ($siteId != $row['sites']) {
            /*skip first iteration*/
            if ($count > 0) {
                /*site id and site name format*/

                /*add to arrays according to field*/
                if ($field == 'Bond') {
                    array_push($bondArray, array(str_replace(' ', ' - ', $siteId), makeSiteNameString($siteId, $masterId), $bond1, $bond2));
                } elseif ($field == 'Angle') {
                    array_push($angleArray, array(str_replace(' ', ' - ', $siteId), makeSiteNameString($siteId, $masterId), $angle1, $angle2));
                } elseif ($field == 'ConstrU') {
                    array_push($constrArray, array($constr1++, $constr2, str_replace(' ', ' - ', $siteId), makeSiteNameString($siteId, $masterId)));
                } elseif ($field == 'Dihedral' && $row['param'] !== 'nmax') {
                    array_push($diheidralArray, $temp_array);
                }
            }
            /*for diheidral array*/
            $temp_array = array();
            $temp_array['Site-ID'] = str_replace(' ', ' - ', $row['sites']);
            $temp_array['Site-Name'] = makeSiteNameString($row['sites'], $masterId);
            /*if field change assign new field type*/
            if ($field !== $row['field']) {
                $field = $row['field'];
            }
            /*assign new site id*/
            $siteId = $row['sites'];
        }

//        var_dump($row);
        /*if bond then assign valus to variable*/
        if ($row['field'] == 'Bond') {
            if ($row['param'] == 'R0') {
                $bond1 = $row['val'];
            }
            if ($row['param'] == 'ForConst') {
                $bond2 = $row['val'];
            }
        } elseif ($row['field'] == 'Angle') {
            if ($row['param'] == 'Angle0')
                $angle1 = $row['val'];
            if ($row['param'] == 'ForConst')
                $angle2 = $row['val'];

        } elseif ($field == 'ConstrU') {
            if ($row['param'] == 'Constraint')
                $constr2 = $row['val'];

        } elseif ($row['field'] == 'Dihedral' && $row['param'] !== 'nmax') {
            /*make array as it is for process further afterwords*/
            $temp_array[$row['param']] = $row['val'];
        }

        /*if last loop add explicitly*/
        if ($count === $numItems - 1) {
            /*add to arrays according to field*/
            if ($field == 'Bond') {
                array_push($bondArray, array(str_replace(' ', ' - ', $siteId), makeSiteNameString($siteId, $masterId), $bond1, $bond2));
            } elseif ($field == 'Angle') {
                array_push($angleArray, array(str_replace(' ', ' - ', $siteId), makeSiteNameString($siteId, $masterId), $angle1, $angle2));
            } elseif ($field == 'ConstrU') {
                array_push($constrArray, array($constr1++, $constr2, str_replace(' ', ' - ', $siteId), makeSiteNameString($siteId, $masterId)));
            } elseif ($field == 'Dihedral' && $row['param'] !== 'nmax') {
                array_push($diheidralArray, $temp_array);
            }
        }
        /*incr*/
        $count++;
    }
//    var_dump($diheidralArray);
    /*return value as one array*/
    $returnArray = array();
    array_push($returnArray, $bondArray, $angleArray, $diheidralArray, $constrArray);
    return $returnArray;
}

function makeSiteNameString($siteIds, $masterId)
{
    $db = new Database();
    /* prepare master sites for name */
    $sites = array();
    $result = $db->selectRecords('SELECT val,site FROM pm_detail d WHERE d.param="SiteID" AND d.master_id =? ORDER BY val ASC', array($masterId));
    foreach ($result as $row) {
        $sites[$row['val']] = $row['site'];
    }

    /*get site name for each site id*/
    $ids = explode(' ', $siteIds);
    $siteName = '';
    foreach ($ids as $i) {
        $siteName = $siteName . ' ' . $sites[$i];
    }
    return str_replace(' ', ' - ', trim($siteName));
}

function timeStamp()
{
    $now = new DateTime();
    return $now->format('d-m-Y');
}

/*func to format the header of detail matrices*/
function toCustomHeader($header)
{
    switch (trim($header)) {
        case "Site":
            return 'Site-ID';
        case "SiteName";
            return 'Site-name';
        case "Mass":
            return 'M / g mol<sup>-1</sup>';
        case "Epsilon":
//            return '<span>&epsilon;</span>';
            return '<span>&epsilon;/k<sub>B</sub></span> / <span>&#8490;</span>';
        case "Sigma":
            return '<span>&sigma;</span> / <span>&#8491;</span>';
        case "Quadrupole":
            return 'Q / D<span>&#8491;</span>';
        case "Theta":
            return '<span>&Theta;</span> / <span>&#xb0;</span>';
        case "Phi":
            return '<span>&Phi;</span> / <span>&#xb0;</span>';
        case "Dipole":
            return '<span>&mu;</span> / D';
        case "Shielding":
            return $header . ' / <span>&#8491;</span>';
        case "Charge":
            return $header . ' / e';
        default:
            return $header;
    }
}

function toCustomDihedralHeader($header)
{
    if ($header == "Site-ID") {
        return "Site-ID's";
    } elseif ($header == "Site-Name") {
        return "Site-names";
    } elseif ($header == "ScaleLJ14") {
        return "LJ<sub>1-4</sub>";
    } elseif ($header == "ScaleEl14") {
        return "Elec.<sub>1-4</sub>";
    } elseif (strpos($header, 'ForConst') !== false) {
        $digit = str_replace('ForConst', '', $header);
        return 'c<sub>' . $digit . '</sub>/k<sub>B</sub> / K ';
    } elseif (strpos($header, 'gamma0') !== false) {
        $digit = str_replace('gamma0', '', $header);
        return '&Phi;<sub>' . $digit . '</sub> / <span>&#xb0;</span> ';
    }
    return $header;
}

function getBracketHeight($line)
{
    $multiplier = 22;
    if ($line == 1) {
        return '30';
    } else {
        return $multiplier * $line;
    }
}

function getBracketWidth($line)
{
    $multiplier = 22;
    if ($line == 1) {

        return '12';
    } elseif ($line == 2) {

        return $multiplier * $line * 0.45;
    } elseif ($line > 7) {

        return $multiplier * $line * 0.20;
    } else {

        return $multiplier * $line * 0.30;
    }
}

function getModelType($lj, $charge, $dipole, $qudrupole)
{
    $ans = '';

    if ($lj != 0 && $lj != 1)
        $ans = $lj . ' L.J. Sites';
    elseif ($lj == 1)
        $ans = $lj . ' L.J. Site';

    if ($charge != 0 && $charge != 1)
        $ans .= (empty($ans) ? '' : ' & ') . $charge . ' Charges';
    elseif ($charge == 1)
        $ans .= (empty($ans) ? '' : ' & ') . $charge . ' Charge';

    if ($dipole != 0 && $dipole != 1)
        $ans .= (empty($ans) ? '' : ' & ') . $dipole . ' Dipoles';
    elseif ($dipole == 1)
        $ans .= (empty($ans) ? '' : ' & ') . $dipole . ' Dipole';

    if ($qudrupole != 0 && $qudrupole != 1)
        $ans .= (empty($ans) ? '' : ' & ') . $qudrupole . ' Quadrupoles';
    elseif ($qudrupole == 1)
        $ans .= (empty($ans) ? '' : ' & ') . $qudrupole . ' Quadrupole';

    return $ans;

}

function getMolecule($masterId)
{
    //declaring variables
    $points = array();
    //getting co-ordinates from database
    $db = new Database();
    $result = $db->selectRecords('SELECT * FROM pm_detail WHERE master_id =?', array($masterId));
    $count = 0;
    $point = null;
    $oth = null;
    $del_val = 'del_val';

    //saving points to array
    foreach ($result as $row) {

        if ($row['param'] == 'SiteID') {
            //for flexible field
            continue;
        }
        if ($row['param'] == 'x') {
            //this is break point of each new point (x) cordinate
            //creating structure of array
            $oth = array(
                'Site' => $del_val,
                'SiteName' => $del_val,
                'Mass' => $del_val,
                'Sigma' => $del_val,
                'Epsilon' => $del_val,
                'Charge' => $del_val,
                'Dipole' => $del_val,
                'Quadrupole' => $del_val,
                'Theta' => $del_val,
                'Phi' => $del_val,
                'Shielding' => $del_val
            );
            $count++;
            $site = $row['site'];
            $sitetype = $row['site_type'];
            $x = $row['val'];

            $point = new Vec();
            $point->setId($count);

            $point->setName($site);
            $point->setSitetype($sitetype);
            $point->setX($x);

            //making table for other pera
            $oth['Site'] = $count;
            $oth['SiteName'] = $row['site'];

            array_push($points, $point);
        } else if ($row['param'] == 'y') {
            $point->setY($row['val']);
        } else if ($row['param'] == 'z') {
            $point->setZ($row['val']);
        } else if ($row['param'] == 'sigma'
            || $row['param'] == 'epsilon' || $row['param'] == 'charge' || $row['param'] == 'mass'
            || $row['param'] == 'theta' || $row['param'] == 'phi'
            || $row['param'] == 'quadrupole' || $row['param'] == 'dipole' || $row['param'] == 'shielding'
        ) {
            $oth[ucwords($row['param'])] = $row['val'];
        }
        $point->setOth($oth);
    }
    //removeing del_val
    foreach ($points as $p) {
        //assign tmp var
        $tmp = $p->getOth();
        //remove all empty elements
        while (($key = array_search($del_val, $tmp))) {
            unset($tmp[$key]);
        }
        $p->setOth($tmp);
    }
    return $points;
}

function splitMolSiteWise($molecule)
{
    $finalMolecule = array();
    //saperate points sitewise (prepare input arrays)
    $lj = array();
    $ch = array();
    $dp = array();
    $qd = array();
    foreach ($molecule as $p) {
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

    $finalMolecule['lj'] = $lj;
    $finalMolecule['ch'] = $ch;
    $finalMolecule['dp'] = $dp;
    $finalMolecule['qd'] = $qd;

    return $finalMolecule;
}

function splitPmatrixSiteWise($pmatrix)
{
    $finalMolecule = array();
    //saperate points sitewise (prepare input arrays)
    $lj = array();
    $ch = array();
    $dp = array();
    $qd = array();
    foreach ($pmatrix as $p) {
        if ($p[0] == 'LJ126') {
            array_push($lj, $p[2]);
        } else if ($p[0] == 'Charge') {
            array_push($ch, $p[2]);
        } else if ($p[0] == 'Dipole') {
            array_push($dp, $p[2]);
        } else if ($p[0] == 'Quadrupole') {
            array_push($qd, $p[2]);
        }
    }

    $finalMolecule['lj'] = $lj;
    $finalMolecule['ch'] = $ch;
    $finalMolecule['dp'] = $dp;
    $finalMolecule['qd'] = $qd;

    return $finalMolecule;
}

function removeQuad($molecule)
{
    if (sizeof($molecule['qd']) > 0) {
        //for each quaderpole
        foreach ($molecule['qd'] as $q) {
            //sigma calculation
            $sigma = 0;
            $cal = 10000;
            $finalSigma = 0;
            foreach ($molecule['lj'] as $l) {
                //storing previous iteration
                $lastCal = $cal;
                $lastSigma = $sigma;
                //current
                $cal = sqrt(
                    pow($l->getX() - $q->getX(), 2)
                    + pow($l->getY() - $q->getY(), 2)
                    + pow($l->getZ() - $q->getZ(), 2)
                );
                $sigma = $l->getOth()['Sigma'];

                //decide final
                if ($lastCal < $cal)
                    $finalSigma = $lastSigma;
                elseif ($lastCal == $cal)
                    $finalSigma = min($lastSigma, $sigma);
                else
                    $finalSigma = $sigma;
            }

            //var for formulas
            $a = $finalSigma / 20;


            //declaring 3 charges for 1 qd
            $c1 = new Vec();
            $c2 = new Vec();
            $c3 = new Vec();

            //1st charge
            $c1->setId(sizeof($molecule['ch']) + 1);
            $c1->setName($q->getName() . '[e_1]');
            $c1->setSitetype('Charge');
            $c1->setX($q->getX() + $a * (sin(deg2rad($q->getOth()['Theta'])) * cos(deg2rad($q->getOth()['Phi']))));
            $c1->setY($q->getY() + $a * (sin(deg2rad($q->getOth()['Theta'])) * sin(deg2rad($q->getOth()['Phi']))));
            $c1->setZ($q->getZ() + $a * cos(deg2rad($q->getOth()['Theta'])));
            //oth
            $oth['Site'] = sizeof($molecule['ch']) + 1;
            $oth['SiteName'] = $q->getName() . '[e_1]';
            $oth['Mass'] = $q->getOth()['Mass'] / 3;
            $oth['Charge'] = (0.2082 * $q->getOth()['Quadrupole']) / (2 * pow($a, 2));
            $oth['Shielding'] = 1;
            $c1->setOth($oth);
            array_push($molecule['ch'], $c1);

            //2st charge
            $c2->setId(sizeof($molecule['ch']) + 1);
            $c2->setName($q->getName() . '[e_2]');
            $c2->setSitetype('Charge');
            $c2->setX($q->getX());
            $c2->setY($q->getY());
            $c2->setZ($q->getZ());
            //oth
            $oth['Site'] = sizeof($molecule['ch']) + 1;
            $oth['SiteName'] = $q->getName() . '[e_2]';
            $oth['Mass'] = $q->getOth()['Mass'] / 3;
            $oth['Charge'] = (-0.2082 * $q->getOth()['Quadrupole']) / pow($a, 2);
            $oth['Shielding'] = 1;
            $c2->setOth($oth);
            array_push($molecule['ch'], $c2);

            //3st charge
            $c3->setId(sizeof($molecule['ch']) + 1);
            $c3->setName($q->getName() . '[e_3]');
            $c3->setSitetype('Charge');
            $c3->setX($q->getX() - $a * (sin(deg2rad($q->getOth()['Theta'])) * cos(deg2rad($q->getOth()['Phi']))));
            $c3->setY($q->getY() - $a * (sin(deg2rad($q->getOth()['Theta'])) * sin(deg2rad($q->getOth()['Phi']))));
            $c3->setZ($q->getZ() - $a * cos(deg2rad($q->getOth()['Theta'])));
            //oth
            $oth['Site'] = sizeof($molecule['ch']) + 1;
            $oth['SiteName'] = $q->getName() . '[e_3]';
            $oth['Mass'] = $q->getOth()['Mass'] / 3;
            $oth['Charge'] = (0.2082 * $q->getOth()['Quadrupole']) / (2 * pow($a, 2));
            $oth['Shielding'] = 1;
            $c3->setOth($oth);
            array_push($molecule['ch'], $c3);
        }
    }

    //removing qd
    $molecule['qd'] = array();
    return $molecule;
}

function removeDipole($molecule)
{
    if (sizeof($molecule['dp']) > 0) {
        //for each quaderpole
        foreach ($molecule['dp'] as $d) {
            //sigma calculation
            $sigma = 0;
            $cal = 10000;
            $finalSigma = 0;
            foreach ($molecule['lj'] as $l) {
                //storing previous iteration
                $lastCal = $cal;
                $lastSigma = $sigma;
                //current
                $cal = sqrt(
                    pow($l->getX() - $d->getX(), 2)
                    + pow($l->getY() - $d->getY(), 2)
                    + pow($l->getZ() - $d->getZ(), 2)
                );
                $sigma = $l->getOth()['Sigma'];

                //decide final
                if ($lastCal < $cal)
                    $finalSigma = $lastSigma;
                elseif ($lastCal == $cal)
                    $finalSigma = min($lastSigma, $sigma);
                else
                    $finalSigma = $sigma;
            }

            //var for formulas
            $a = $finalSigma / 40;


            //declaring 2 charges for 1 dp
            $c1 = new Vec();
            $c2 = new Vec();

            //1st charge
            $c1->setId(sizeof($molecule['ch']) + 1);
            $c1->setName($d->getName() . '[e_1]');
            $c1->setSitetype('Charge');
            $c1->setX($d->getX() + $a * (sin(deg2rad($d->getOth()['Theta'])) * cos(deg2rad($d->getOth()['Phi']))));
            $c1->setY($d->getY() + $a * (sin(deg2rad($d->getOth()['Theta'])) * sin(deg2rad($d->getOth()['Phi']))));
            $c1->setZ($d->getZ() + $a * cos(deg2rad($d->getOth()['Theta'])));
            //oth
            $oth['Site'] = sizeof($molecule['ch']) + 1;
            $oth['SiteName'] = $d->getName() . '[e_1]';
            $oth['Mass'] = $d->getOth()['Mass'] / 2;
            $oth['Charge'] = (-0.2082 * $d->getOth()['Dipole']) / (2 * $a);
            $oth['Shielding'] = 1;
            $c1->setOth($oth);
            array_push($molecule['ch'], $c1);

            //2nd charge
            $c2->setId(sizeof($molecule['ch']) + 1);
            $c2->setName($d->getName() . '[e_2]');
            $c2->setSitetype('Charge');
            $c2->setX($d->getX() - $a * (sin(deg2rad($d->getOth()['Theta'])) * cos(deg2rad($d->getOth()['Phi']))));
            $c2->setY($d->getY() - $a * (sin(deg2rad($d->getOth()['Theta'])) * sin(deg2rad($d->getOth()['Phi']))));
            $c2->setZ($d->getZ() - $a * cos(deg2rad($d->getOth()['Theta'])));
            //oth
            $oth['Site'] = sizeof($molecule['ch']) + 1;
            $oth['SiteName'] = $d->getName() . '[e_3]';
            $oth['Mass'] = $d->getOth()['Mass'] / 3;
            $oth['Charge'] = (-0.2082 * $d->getOth()['Dipole']) / (2 * $a);
            $oth['Shielding'] = 1;
            $c2->setOth($oth);
            array_push($molecule['ch'], $c2);
        }
    }

    //removing dp
    $molecule['dp'] = array();
    return $molecule;
}


function modifyArrForGro($lamArray)
{
    // initial modyfing arrays
//MASS
    foreach ($lamArray['MASS'] as $key => $val) {
        if ($val == 0.000001) {
            $lamArray['MASS'][$key] = 0.00000;
        } else {
            $lamArray['MASS'][$key] = round($val, 4);
        }
    }
//EPS
    foreach ($lamArray['EPS'] as $key => $val) {
        $lamArray['EPS'][$key] = round($val * 96.48533274, 4);
    }
//SIG
    foreach ($lamArray['SIG'] as $key => $val) {
        $lamArray['SIG'][$key] = round($val * 0.1, 4);
    }
    return $lamArray;
}

?>