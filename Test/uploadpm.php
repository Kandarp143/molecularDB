<?php
require '../database.php';
$pdo = Database::connect();


// Count # of uploaded files in array
if (isset($_FILES['fileToUpload'])) {
    $total = count($_FILES['fileToUpload']['name']);
//    echo $total . '<br/>';
// Loop through each file
    for ($i = 0; $i < $total; $i++) {
        //get file raw data
        $filename = $_FILES['fileToUpload']['name'][$i];
        echo 'FileNAme: ' . $filename . '<br/>';
        $file = $_FILES['fileToUpload']['tmp_name'][$i];
//        echo 'FilePath: ' . $file . '<br/>';

        //Read and Insert data
        $f = fopen($file, "r") or exit("Unable to open file!");
        $members = array();
        while (!feof($f)) {
            $members[] = fgets($f);
        }

        //trim and remove blank lines
        $trimmed_array = array_values(array_filter($members, "trim"));
        $fileArray = array_map('trim', $trimmed_array);

        $arr = explode(".", $filename);
        $first = $arr[0];

        $sql = "SELECT master_id FROM pm_master WHERE filename ='" . $first . "';";
        foreach ($pdo->query($sql) as $row) {
            $master_id = $row["master_id"];
            echo "id: " . $master_id . "<br>";
        }

        //make master data array
        $count = 0;
        $finalData = array();

        foreach ($fileArray as $key => $value) {
            //remove unused lines
            if (strpos($value, "NSiteTypes") === 0 ||
                strpos($value, "NSites") === 0 ||
                strpos($value, "NRotAxes") === 0
            ) {
//                echo "removing unused element <br>";
                unset($fileArray[$key]);
            }

        }
        //convert it to key value pair
        foreach ($fileArray as $key => $value) {
            $tempArray = explode("=", $value);
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
        //LOOP THROUGH MASTER DATA AND INSERT
        foreach ($finalData as $key => $value) {
            if (strpos($key, "#") === 0 || strpos($key, "SiteType") === 0) {
                if (strpos($key, "SiteType") === 0) {
                    $sitetype = $value;
                }
                if (strpos($key, "#") === 0) {
                    $site = str_replace('#', '', $value);
                }

            } else {
                $param = current(explode("$", $key));
                if (isset($master_id)) {
                    //inserting data
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO pm_detail (master_id,site_type,site,param,val) values(?, ?, ?,?,?)";
                    $q = $pdo->prepare($sql);
                    $suc = $q->execute(array($master_id, trim($sitetype, " "), trim($site, " "), trim($param, " "), trim($value, " ")));

                } else {
                    echo 'Note this File : ' . $filename . '<br/>';
                }

            }
        }


    }

}
Database::disconnect();

?>
<form action="" method="post"
      enctype="multipart/form-data">
    <table>
        <tr>
            <th colspan="2"> Step 2 : Upload PM file (Please keep filename same as substance name
                )
            </th>
        </tr>

        <tr>
            <td>
                <input type="file" name="fileToUpload[]" id="fileToUpload" multiple="multiple">
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="UploadFile" name="submit">
            </td>
        </tr>
    </table>
</form>