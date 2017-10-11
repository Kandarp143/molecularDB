<?php
/**
 * Created by PhpStorm.
 * User: jp
 * Date: 26.12.16
 * Time: 14:50
 */
require '../database.php';
//$txt_file = file_get_contents('../pm/literatur_Manual.bib');
$rows = explode("\n", $txt_file);
array_shift($rows);
//Variables
$attr = "";
$value = "";
$ref = "";
$type = "";
$count = 0;
foreach ($rows as $row => $data) {
    //get row data
    if ($row == 0) {
        $row++;
        continue;
    }
    if ($row == 1) {
        $row++;
        continue;
    }
    if ($row == 2) {
        $row++;
        continue;
    }

    $row_data = explode('@', $data);
    if (strpos($row_data[1], 'Book') !== false || strpos($row_data[1], 'Article') !== false
        || strpos($row_data[1], 'PhdThesis') !== false
    ) {
        $type = strtok($row_data[1], '{');
        $ref = substr($row_data[1], strpos($row_data[1], "{") + 1);
        $ref = rtrim(trim($ref), ',');
        $count = $count + 1;
//        echo '-------------------------' . $count . '-' . $type . ' : ' . $ref . '-------------------------------------<br/>';
    }
    $lHS_data = explode('=', $data);
    if ($lHS_data[1] == null) {
        unset($lHS_data[0]);
        unset($lHS_data[1]);
    } else {
        $attr = trim($lHS_data[0]);
        $value = trim(trim($lHS_data[1]), '{},');
//        echo $attr . '  =  ' . $value . '<br/>';
        echo $count . '     |   ' . $type . '     |   ' . $ref . '     |   ' . $attr . '     |   ' . $value . '<br/>';

        // insert data
        try {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'INSERT INTO pm_bib(bib_key,bib_type,bib_title,param,value) VALUES (?,?,?,?,?)';
            $q = $pdo->prepare($sql);
            $suc = $q->execute(array($count, $type, $ref, $attr, $value));
            Database::disconnect();
        } catch (Exception $e) {
            echo $e;
        }
    }

}

?>