<?php

/**
 * Created by PhpStorm.
 * User: Kandarp
 * Date: 22/02/2017
 * Time: 10:29 AM
 */
class Vec
{
    /*Var*/
    private $id = 0;
    private $name = '';
    private $x = 0;
    private $y = 0;
    private $z = 0;
    private $len = 0;
    private $sitetype = 0;
    private $oth = array();
    private $isSame = false;
    private $ref = 0;

    /* Funcation */

    /*check 3 points smilarity and retrun 3points */
    public function updateSamePoints($checkPoints, $masterPoints)
    {
        $a = $checkPoints[0];
        $b = $checkPoints[1];
        $c = $checkPoints[2];
        $mst = $checkPoints[3];
        $mstId = $mst->getId();
        $count = 0;
        $isForward = false;
        $isForward2 = false;
        do {
            $oneMore = false;
            if (($this->isSame($a, $c) || $this->isSame($b, $c)) || $c->getName() == $mst->getName()) {
//                echo 'C Badlo<br/>';
                //c badlo
                $key = array_search($c, $masterPoints);
//                echo 'MST : ' . $mstId . ' KEY:' . $key . '<br/>';
//                echo ' OLD : ' . $c->getName();
                if (!$isForward)
                    $isForward = isset($masterPoints[$key - 1]) ? false : true;

                if ($isForward) {
                    $c = isset($masterPoints[$key + 1]) ? $masterPoints[$key + 1] : $masterPoints[$key];
//                    $key = $key + 1;
                } else {
                    $c = isset($masterPoints[$key - 1]) ? $masterPoints[$key - 1] : $masterPoints[$key];
                }
//                echo '<br/>IS FORWARD' . $isForward . '-' . $key;
//                echo '<br/>NEW : ' . $c->getName();
                $oneMore = true;
                $count += 1;
            }

            if ($this->isSame($a, $b) || $b->getName() == $mst->getName()) {

//                echo 'B Badlo<br/>';
                //d badlo
                $key = array_search($c, $masterPoints);
//                echo ' OLD : ' . $b->getName();
                if (!$isForward2)
                    $isForward2 = isset($masterPoints[$key - 1]) ? false : true;

                if ($isForward2) {
                    $b = isset($masterPoints[$key + 1]) ? $masterPoints[$key + 1] : $masterPoints[$key];
//                    $key = $key + 1;
                } else {
                    $b = isset($masterPoints[$key - 1]) ? $masterPoints[$key - 1] : $masterPoints[$key];
                }
//                echo '<br/>IS FORWARD' . $isForward . '-' . $key;
//                echo '<br/>NEW : ' . $b->getName();
                $oneMore = true;
                $count += 1;
            }
        } while ($oneMore && $count < 10);

        $ans = null;
        $count == 0 ? $ans : $ans = array($a, $b, $c);
        return $ans;
    }

    public
    function isSame(Vec $p1, Vec $p2)
    {

        $ans = false;
        if ($p1->getX() == $p2->getX() && $p1->getY() == $p2->getY() && $p1->getZ() == $p2->getZ()) {
//            echo '<br/>SAME :' . $p1->getName() . '-' . $p2->getName() . '<br/>';
            $ans = true;

        }
        return $ans;
    }

    /*for the special point*/

    public
    function getIsSame()
    {
        return $this->isSame;
    }

    public
    function setIsSame($points)
    {
        foreach ($points as $pp) {
            if ($this->getX() == $pp->getX() && $this->getY() == $pp->getY() && $this->getZ() == $pp->getZ() && $this != $pp
                && $this->getId() > $pp->getId()
            ) {
                $this->isSame = true;
                $this->setRef($pp->getId());
            }
        }
    }

    public
    function getX()
    {
        return $this->x;
    }

    public
    function setX($x)
    {
        $this->x = $x;

    }

    public
    function getY()
    {
        return $this->y;
    }

    /*funcation to set (x,y,z) of point*/

    public
    function setY($y)
    {
        $this->y = $y;
    }

    /* subtract one point from another (for vector = pass (point2,point1)*/

    public
    function getZ()
    {
        return $this->z;
    }

    public
    function setZ($z)
    {
        $this->z = $z;
    }

    public
    function getId()
    {
        return $this->id;
    }

    public
    function setId($id)
    {
        $this->id = $id;
    }

    public
    function setCordinatefromVec($v)
    {
        $theta = $v->getOth()['Theta'];
        $phi = $v->getOth()['Phi'];

        $this->x = sin(deg2rad($theta)) * cos(deg2rad($phi)) + $v->getX();
        $this->y = sin(deg2rad($theta)) * sin(deg2rad($phi)) + $v->getY();
        $this->z = cos(deg2rad($theta)) + $v->getZ();
//        echo 'Printing Co-ordinates<br/>';
//        echo 'X from :' . $v->getX() . '  |  ' . sin(deg2rad($theta)) . '  |  ' . cos($phi) . '         X  :' . $this->x . '<br/>';
//        echo 'Y from :' . $v->getY() . '  -  ' . $theta . '  -  ' . $phi . '         Y  :' . $this->y . '<br/>';
//        echo 'Z from :' . $v->getZ() . '  -  ' . $theta . '  -  ' . $phi . '         Z  :' . $this->z . '<br/><br/><br/><br/>';
    }

    public
    function getDistance($p1, $p2)
    {
//        echo 'Distance Between : ' . $p1->getName() . '(' . $p1->getX() . ',' . $p1->getY() . ',' . $p1->getZ() . ',)' .
//            ' AND ' . $p2->getName() . '(' . $p1->getX() . ',' . $p1->getY() . ',' . $p1->getZ() . ',)' . '<br/>';
        $Vec = new Vec();
        $Vec->subVec($p1, $p2);
        return $Vec->len();
    }

    public
    function subVec(Vec $c1, Vec $c2)
    {
        $this->x = 0;
        $this->y = 0;
        $this->z = 0;
        $this->x = $c2->getX() - $c1->getX();
        $this->y = $c2->getY() - $c1->getY();
        $this->z = $c2->getZ() - $c1->getZ();
//        echo 'V : (' . $this->x . ' , ' . $this->y . ' , ' . $this->z . ') : ' . $c1->getName() . $c2->getName() . '<br/>';
    }

    public
    function len()
    {
        return (sqrt($this->x * $this->x +
            $this->y * $this->y +
            $this->z * $this->z));
    }

    /*Getter and Setter*/

    public
    function getAngle($p1, $p2, $p3)
    {

        $vector = new Vec();

        $v1 = new Vec();
        $v1->subVec($p1, $p2);
        $v1->setLen($v1->len());

        $v2 = new Vec();
        $v2->subVec($p2, $p3);
        $v2->setLen($v1->len());
//        echo 'V1 : ' . $p1->getName() . $p2->getName() . '<br/>';
//        echo 'V2 : ' . $p2->getName() . $p3->getName() . '<br/>';

        return $vector->angleXY($v1, $v2);

    }

    public
    function angleXY(Vec $c1, Vec $c2)
    {

        //custom formula
        $dotp = $this->dotVecAngle($c1, $c2);
        $len = $c1->len() * $c2->len();
        if ($len != 0) {
            $val = $dotp / $len;
            $rad = acos($val);
            $ag = rad2deg($rad);
            $intpart = floor($ag);    // results in 3
            $fraction = $ag - $intpart; // results in 0.75


//            echo '$intpart : ' . $intpart . '<br/>';
//            echo '$fraction : ' . $fraction . '<br/>';
            if ($fraction > 0.9900 || ($fraction < 0.001 && $fraction != 0)) {
//                echo 'Angle : ' . $ag . '<br/>';
//                echo '$fraction : ' . $fraction . '<br/>';
//                echo ' ' . 'TRUE' . round($ag) . '<br/><br/><br/>';
                $ag = round($ag);
            }
            return $ag;
        } else {
            return 0;
        }
    }

    public
    function dotVecAngle(Vec $c1, Vec $c2)
    {
//        echo '<br/>((' . $c1->getX() . ' * ' . $c2->getX() . ') + (' . $c1->getY() . ' * ' . $c2->getY() . ') + (' . $c1->getZ() . ' * ' . $c2->getZ() . ')) = ' . (($c1->getX() * $c2->getX()) + ($c1->getY() * $c2->getY()) + ($c1->getZ() * $c2->getZ())) . '<br/><br/>';
        return ((-$c1->getX() * $c2->getX()) + (-$c1->getY() * $c2->getY()) + (-$c1->getZ() * $c2->getZ()));
    }

    public
    function getAngleSign($p1, $p2, $p3, $p4, $currentSign)
    {

        $v1 = new Vec();
        $v1->subVec($p1, $p2);
        $v1->setLen($v1->len());


        $v2 = new Vec();
        $v2->subVec($p2, $p3);
        $v2->setLen($v2->len());

        $v3 = new Vec();
        $v3->subVec($p3, $p4);
        $v3->setLen($v3->len());

        $norm = new Vec();
        $norm = $norm->normVec($v1, $v2);

        $norm2 = new Vec();
        $norm2 = $norm2->normVec($v2, $v3);

        $signA = $norm->angleXY($norm, $norm2);
        if ($signA <= 90) {
            $currentSign = $currentSign * (-1);
        }

//        echo '(' . $v1->getX() . ' , ' . $v1->getY() . ' , ' . $v1->getZ() . ')'
//            . '(' . $v2->getX() . ' , ' . $v2->getY() . ' , ' . $v2->getZ() . ') ='
//        echo '(' . $norm->getX() . ' , ' . $norm->getY() . ' , ' . $norm->getZ() . ') AND ';

//        echo '(' . $v1->getX() . ' , ' . $v1->getY() . ' , ' . $v1->getZ() . ')'
//            . '(' . $v2->getX() . ' , ' . $v2->getY() . ' , ' . $v2->getZ() . ') ='
//        echo '(' . $norm2->getX() . ' , ' . $norm2->getY() . ' , ' . $norm2->getZ() . ')' . $signA * $sign . '<br/>';
//        echo round($v1->len(), 4) . ' From : ' . $p1->getName() . ' --- ' . $p2->getName() . '<br/>';
//        echo round($v2->len(), 4) . ' From : ' . $p2->getName() . ' --- ' . $p3->getName() . '<br/>';
//        echo round($v3->len(), 4) . ' From : ' . $p3->getName() . ' --- ' . $p4->getName() . '<br/>';

        return $currentSign;
    }

    public
    function normVec(Vec $c1, Vec $c2)
    {
        $cx = $c1->getY() * $c2->getZ() - $c1->getZ() * $c2->getY();
        $cy = $c1->getZ() * $c2->getX() - $c1->getX() * $c2->getZ();
        $cz = $c1->getX() * $c2->getY() - $c1->getY() * $c2->getX();
        $this->x = $cx;
        $this->y = $cy;
        $this->z = $cz;
        $v = new Vec();
        $v->setCordinate($cx, $cy, $cz);
//        echo '(' . $v->getX() . ',' . $v->getY() . ',' . $v->getZ() . ')-(';
        return $v;
    }

    function setCordinate($x, $y, $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;

    }

    public
    function getDihedral($p1, $p2, $p3, $p4)
    {

        $vector = new Vec();

        $v1 = new Vec();
        $v1->subVec($p1, $p2);
        $v1->setLen($v1->len());


        $v2 = new Vec();
        $v2->subVec($p2, $p3);
        $v2->setLen($v2->len());

        $v3 = new Vec();
        $v3->subVec($p3, $p4);
        $v3->setLen($v3->len());
//        echo round($v1->len(), 4) . ' From : ' . $p1->getName() . ' --- ' . $p2->getName() . '<br/>';
//        echo round($v2->len(), 4) . ' From : ' . $p2->getName() . ' --- ' . $p3->getName() . '<br/>';
//        echo round($v3->len(), 4) . ' From : ' . $p3->getName() . ' --- ' . $p4->getName() . '<br/>';
//        echo 'From New <br/>';
//        echo round($v1->len(), 4) . '   --   ' . round($v2->len(), 4) . '   --   ' . round($v3->len(), 4) . '  --   ' . '<br/>';
//        echo ' From : ' . round($v1->getLen(), 2) . ' - ' . round($v2->getLen(), 2) . ' - ' . round($v3->getLen(), 2) . '<br/>';
        return $vector->angleXYZ($v1, $v2, $v3);
    }

    public
    function angleXYZ(Vec $c1, Vec $c2, Vec $c3)
    {
//        echo round($c1->len(), 4) . '   --   ' . round($c2->len(), 4) . '   --   ' . round($c3->len(), 4) . '  --   ' . '<br/>';

        //custom formula
        $norm = $this->normVec($c1, $c2);
        $dotp = $this->dotVec($norm, $c3);
        $len = $norm->len() * $c3->len();
        if ($len != 0) {
            $val = $dotp / $len;
            $rad = asin($val);
            return rad2deg($rad);
        } else {
            return 0;
        }
    }

    public
    function dotVec(Vec $c1, Vec $c2)
    {
//        echo '<br/>((' . $c1->getX() . ' * ' . $c2->getX() . ') + (' . $c1->getY() . ' * ' . $c2->getY() . ') + (' . $c1->getZ() . ' * ' . $c2->getZ() . ')) = ' . (($c1->getX() * $c2->getX()) + ($c1->getY() * $c2->getY()) + ($c1->getZ() * $c2->getZ())) . '<br/><br/>';
        return (($c1->getX() * $c2->getX()) + ($c1->getY() * $c2->getY()) + ($c1->getZ() * $c2->getZ()));
    }

    public
    function unitVec(Vec $v)
    {
        $u = new Vec();

        $len = $v->len();
        $ux = 0;
        $uy = 0;
        $uz = 0;

        if ($v->getX() != 0 && $len != 0)
            $ux = $v->getX() / $len;

        if ($v->getY() != 0 && $len != 0)
            $uy = $v->getY() / $len;

        if ($v->getZ() != 0 && $len != 0)
            $uz = $v->getZ() / $len;

        $u->setX($ux);
        $u->setY($uy);
        $u->setZ($uz);
        return $u;
    }

    public
    function getRef()
    {
        return $this->ref;
    }

    public
    function setRef($ref)
    {
        $this->ref = $ref;
    }

    public
    function getLen()
    {
        return $this->len;
    }

    public
    function setLen($len)
    {

        $this->len = $len;
    }

    public
    function getOth()
    {
        return $this->oth;
    }

    public
    function setOth($oth)
    {
        $this->oth = $oth;
    }

    public
    function getSitetype()
    {
        return $this->sitetype;
    }

    public
    function setSitetype($sitetype)
    {
        $this->sitetype = $sitetype;
    }

    public
    function getName()
    {
        return $this->name;
    }

    public
    function setName($name)
    {
        $this->name = $name;
    }

}