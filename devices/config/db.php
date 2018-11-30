<?php
$system_path = 'system';
// Path to the system directory
define('BASEPATH', $system_path);
define('ENVIRONMENT', 'production');

$baseDb = dirname(dirname(__FILE__));
// echo $base.'\application\config\database.php';
require($baseDb.'/application/config/database.php');


$hostname = $db['default']['hostname'];
$username = $db['default']['username'];
$password = $db['default']['password'];
$database = $db['default']['database']; 

/* echo $hostname;
exit; */

 
// $config = require($baseDb.'/devices/config/config.php');
//$config = require('config.php');

/*
$hostname = $config['dbLocation'];
$username = $config['dbUser'];
$password = $config['dbPassword'];
$database = $config['dbName'];  */

$mysqli = new mysqli($hostname, $username, $password, $database);
mysqli_set_charset($mysqli, 'utf8');

?>