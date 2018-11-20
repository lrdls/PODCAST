<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$base = dirname(dirname(__FILE__));
require($base.'/config/db.php');
$config = require($base.'/config/config.php');


//$parent_folder = basename(dirname(__FILE__, 2));

/* $hostname = $config['dbLocation'];
$username = $config['dbUser'];
$password = $config['dbPassword'];
$database = $config['dbName'];  */

//////////////////////////// DONT CHANGE ORDER OF THIS BLOCK !!!
$extToExclude = $config['extToExclude']; 
$extToExclude2 = array('ini','htaccess');
$extToExclude = array_merge($extToExclude, $extToExclude2);
$extToExclude = array_map('strtolower', $extToExclude);
define("EXT_TOEXCLUDE_DUMP",$extToExclude); 
$config = require($base.'/config/fcts.php'); // after define !!!
//////////////////////////////////////////////////////////// END 

$dateNow = date("Ymd-his");
$dirSql = 'sql';
$pathSql = $base.'/config/'.$dirSql; //////////////////////////////////////////////////////// realpath
$dir = $base.'/config/'.$dirSql.'/'.$dateNow; /////////////////////////////////////////////// realpath
$zipName = 'sql-'.$dateNow.'.zip';
$ZipDirProv = $base.'/config/'.$dirSql.'/'.$zipName; //////////////////////////////////////// realpath
$ZipDirProvRename = $dir.'/'.$zipName;
$dirIMG = $base.'/config/'.$dirSql.'/'.$dateNow.'/IMG'; ///////////////////////////////////// realpath
$src = str_replace('/devices','',$base).'/IMG'; ///////////////////////////////////////////// realpath
$pathDatabase = $dir.'/'.strtoupper($database).'dump-'.$dateNow.'.sql'; ///////////////////// realpath
$pathTmp = $base.'/config/'.$dirSql.'/'.strtoupper($database).'dump-'.$dateNow.'.sql'; ////// realpath

$n_zip_folder = count_dirZip($pathSql);

if($n_zip_folder > 1){
    // get previous save infos size
    $result = get_PreviousSavedInfos($pathSql);
    $sizeZipPrevious = $result[0];
    $sizeSqlPrevious = $result[1];
}

createZip_dir($dirSql,$dir,$dirIMG,$src,$ZipDirProv,$zipName,$ZipDirProvRename);
dump_database($pathDatabase,$username,$password,$hostname,$database);

// create tmp sql to compare
copy($pathDatabase, $pathTmp);

if($n_zip_folder > 1){
    // get current save infos size
    $result = get_CurrentSavedInfos($pathSql);
    $sizeZipCurrent = $result[0];
    $sizeSqlCurrent = $result[1];
    if($sizeZipPrevious == $sizeZipCurrent && $sizeSqlPrevious == $sizeSqlCurrent){
        echo 'true, same zip and sql size';
        $dir = $pathSql.'/'.$dateNow;
        rrmdir($dir);
    }
    // gestion max sauvegardes
    $max_save = 20;
    $FolderList = getList($pathSql,'zip');
    rsort($FolderList);
    foreach($FolderList as $key=>$value) {
        if($key >= $max_save){
            $zipFile = $pathSql.'/'.$value.'<br>';
            $a = explode('/',$zipFile);
            $e = end($a);
            $stringToRemove = '/'.$e;
            $folderToDelete = str_replace($stringToRemove,"",$zipFile);
            rrmdir($folderToDelete);
        }
    }
}

// delete tmp sql
unlink($pathTmp);

?>