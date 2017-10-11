<?php
/**
 * Created by PhpStorm.
 * User: k4tru
 * Date: 6/14/2017
 * Time: 9:25 PM
 */
/********************************************************** LOCALHOST OR DEFAULTS  ************************************/

//server name
$serverName = $_SERVER['SERVER_NAME'];

//database config
$dbName = 'molecule_db';
$dbHost = 'localhost';
$dbUsername = 'root';
$dbUserPassword = 'password';

//notication email
$emailDev = 'kppatel14392@gmail.com';
$emailAdmin = 'er.ikndp@gmail.com';

//delete password
$deletePassword = 'admin';

//rootpath
$rootLog = 'gen/log/';
$rootProfileImg = 'img/profile/';
$rootGenPM = 'gen/pm/';
$rootGenLS = 'gen/ls/';

//norman page example molecule
$normanEg = 135;

if ($serverName == 'thermovm.mv.uni-kl.de') {
    /********************************************************** PRODUCTION - UNI SERVER *******************************/

    //db-config
    $dbName = 'moleculedb';
    $dbHost = 'localhost';
    $dbUsername = 'moleculedb';
    $dbUserPassword = 'W/o:="g#"gwK&ARA/pbxfF0gV';
    //email
    $emailAdmin = 'simon.stephan@mv.uni-kl.de';


} elseif ($serverName == 'kpatel.bplaced.net') {
    /********************************************************** BPLACED - TEMP TEST ***********************************/
    $dbName = 'kpatel';
    $dbHost = 'localhost';
    $dbUsername = 'kpatel';
    $dbUserPassword = 'xrStwTwRjzdWAcSX';
}


//defining all as global constant
define("serverName", $serverName);
define("dbName", $dbName);
define("dbHost", $dbHost);
define("dbUsername", $dbUsername);
define("dbUserPassword", $dbUserPassword);
define("emailDev", $emailDev);
define("emailAdmin", $emailAdmin);
define("deletePassword", $deletePassword);
define("rootLog", $rootLog);
define("rootProfileImg", $rootProfileImg);
define("rootGenPM", $rootGenPM);
define("rootGenLS", $rootGenLS);
define("normanEg", $normanEg);

?>