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
    if ($siteType === 'LJ126')
        $siteType = 'LJ 12- 6';
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
        return $ans;
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
function makeZmatrix($masterId, $disp_sh)
{
    $retrunArray = array();

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
            || $row['param'] == 'quadrupole' || $row['param'] == 'dipole'
        ) {
            $oth[ucwords($row['param'])] = $row['val'];
        } else if ($row['param'] == 'shielding' && $disp_sh != 0) {
            $oth[ucwords($row['param'])] = $row['val'];
        }

        $point->setOth($oth);
    }

    //removeing del_val
    foreach ($points as $p) {
        $tmp = $p->getOth();
        while (($key = array_search($del_val, $tmp))) {
            unset($tmp[$key]);
        }
        $p->setOth($tmp);

        $p->setIsSame($points);
//        echo 'OLD Ponits :' . $p->getName() . '<br/>';
    }

    //prepareing display array
    $zmatrix = array();
    $pmatrix = array();
    $markerId = 0;

    $caller = new Vec();
//    echo '<pre>';
//    echo var_dump($points);
//    echo '</pre>';

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
        return "Site-name's";
    } elseif (strpos($header, 'ForConst') !== false) {
        $digit = str_replace('ForConst', '', $header);
        return 'C<sub>' . $digit . '</sub> / k<sub>B</sub> / k ';
    } elseif (strpos($header, 'gamma0') !== false) {
        $digit = str_replace('gamma0', '', $header);
        return '&phi;<sub>' . $digit . '</sub> / <span>&#xb0;</span> ';
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

?>