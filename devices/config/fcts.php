<?php

/**
 * Recursively move files from one directory to another
 * 
 * @param String $src - Source of files being moved
 * @param String $dest - Destination of files being moved
 */
function rmoveDES($src, $dest){
    // print_r(EXT_TOEXCLUDE_DUMP);  
    $extToExclude = EXT_TOEXCLUDE_DUMP;

    // If source is not a directory stop processing
    if(!is_dir($src)) return false;

    // If the destination directory does not exist create it
    if(!is_dir($dest)) { 
        if(!mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }    
    }

    // Open the source directory to read in files
    $i = new DirectoryIterator($src);
    foreach($i as $f) {
        if($f->isFile()) {
            if (!in_array(strtolower($ext), $extToExclude)) {
                //echo $ext;
                rename($f->getRealPath(), "$dest/" . $f->getFilename());
            }
        } else if(!$f->isDot() && $f->isDir()) {
            rmove($f->getRealPath(), "$dest/$f");
            unlink($f->getRealPath());
        }
    }
    unlink($src);
}

/**
 * Recursively copy files from one directory to another
 * 
 * @param String $src - Source of files being moved
 * @param String $dest - Destination of files being moved
 */
function rcopy($src, $dest){
    // print_r(EXT_TOEXCLUDE_DUMP);  
    $extToExclude = EXT_TOEXCLUDE_DUMP;

    // If source is not a directory stop processing
    if(!is_dir($src)) return false;

    // If the destination directory does not exist create it
    if(!is_dir($dest)) { 
        if(!mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }    
    }

    // Open the source directory to read in files
    $i = new DirectoryIterator($src);
    foreach($i as $f) {
        if($f->isFile()) {
            $ext = pathinfo($f, PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), $extToExclude)) {
                //echo $ext;
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
            }
        } else if(!$f->isDot() && $f->isDir()) {
            rcopy($f->getRealPath(), "$dest/$f");
        }
    }
}

function zipDump($dest,$dirZipProv){

    $rootPath = realpath($dest);

    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($dirZipProv, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file){
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();

}

function rrmdir($source, $removeOnlyChildren = false){
    if(empty($source) || file_exists($source) === false){
        return false;
    }
    if(is_file($source) || is_link($source)){
        return unlink($source);
    }

    $files = new RecursiveIteratorIterator
    (
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    //$fileinfo as SplFileInfo
    foreach($files as $fileinfo){
        if($fileinfo->isDir()){
            if(rrmdir($fileinfo->getRealPath()) === false){
                return false;
            }
        }
        else{
            if(unlink($fileinfo->getRealPath()) === false){
                return false;
            }
        }
    }

    if($removeOnlyChildren === false){
        return rmdir($source);
    }

    return true;
}

function createZip_dir($dirSql,$dir,$dirIMG,$src,$ZipDirProv,$zipName,$ZipDirProvRename){
    $oldmask = umask(0);
    @mkdir($dirSql, 0777);
    umask($oldmask);
    $oldmask = umask(0);
    @mkdir($dir, 0777);
    umask($oldmask);
    $oldmask = umask(0);
    @mkdir($dirIMG, 0777);
    umask($oldmask);

    rcopy($src, $dirIMG );                     
    zipDump($dirIMG,$ZipDirProv);                     
    rrmdir($dir, 'false');                     
    rename($ZipDirProv, $ZipDirProvRename );
}

function dump_database($pathDatabase,$username,$password,$hostname,$database){
    echo "<h3>Backing up database to `<code>{$pathDatabase}</code>`</h3>";
    exec("mysqldump --user={$username} --password={$password} --host={$hostname} {$database} --result-file={$pathDatabase} 2>&1", $output);
    var_dump($output);
}

function count_dirZip($pathSql){
    $i = 0; 
    if ($handle = opendir($pathSql)) {
        while (($file = readdir($handle)) !== false){
            if (!in_array($file, array('.', '..')) && !is_dir($pathSql.$file)) 
                $i++;
        }
    }
    return $i;
}

function getList($dir,$ext) {
    $result = [];
    foreach(scandir($dir) as $filename) {
      if ($filename[0] === '.') continue;
      $filePath = $dir . '/' . $filename;
      if (is_dir($filePath)) {
        foreach (getList($filePath,$ext) as $childFilename) {
            $file = $filename . '/' . $childFilename;
            $info = new SplFileInfo($file);
            $get_ext = $info->getExtension();
            if($get_ext==$ext){
                $result[] = $file;
            }
        }
      } else {
        $info = new SplFileInfo($filename);
        $get_ext = $info->getExtension();
        if($get_ext==$ext){
            $result[] = $filename;
        }
      }
    }
    return $result;
}

function getZipSize($file,$pathSql) {
    return filesize($pathSql.'/'.$file);
}

function get_CurrentSavedInfos($pathSql){
    // get zip size
    $result = [];
    $ZipList = getList($pathSql,'zip');
    rsort($ZipList);
    $sizeZipCurrent = getZipSize($ZipList[0],$pathSql);
    // get sql size
    $ZipList = getList($pathSql,'sql');
    rsort($ZipList);
    $sizeSqlCurrent = getZipSize($ZipList[0],$pathSql);
    $result[] = $sizeZipCurrent;
    $result[] = $sizeSqlCurrent;
    return $result;  
}

function get_PreviousSavedInfos($pathSql){
    // get zip size
    $result = [];
    $ZipList = getList($pathSql,'zip');
    rsort($ZipList);
    $sizeZipPrevious = getZipSize($ZipList[0],$pathSql);
    // get sql size
    $ZipList = getList($pathSql,'sql');
    rsort($ZipList);
    $sizeSqlPrevious = getZipSize($ZipList[0],$pathSql);
    $result[] = $sizeZipPrevious;
    $result[] = $sizeSqlPrevious;
    return $result;  
}

/**
* This function returns the maximum files size that can be uploaded 
* in PHP
* @returns int File size in bytes
**/
function getMaximumFileUploadSize()  
{  
    return min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize')));  
}  

/**
* This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
* 
* @param string $sSize
* @return integer The value in bytes
*/
function convertPHPSizeToBytes($sSize)
{
    //
    $sSuffix = strtoupper(substr($sSize, -1));
    if (!in_array($sSuffix,array('P','T','G','M','K'))){
        return (int)$sSize;  
    } 
    $iValue = substr($sSize, 0, -1);
    switch ($sSuffix) {
        case 'P':
            $iValue *= 1024;
            // Fallthrough intended
        case 'T':
            $iValue *= 1024;
            // Fallthrough intended
        case 'G':
            $iValue *= 1024;
            // Fallthrough intended
        case 'M':
            $iValue *= 1024;
            // Fallthrough intended
        case 'K':
            $iValue *= 1024;
            break;
    }
    return (int)$iValue;
}  

function formatBytes($size, $precision = 0){
    $unit = ['Byte','KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];

    for($i = 0; $size >= 1024 && $i < count($unit)-1; $i++){
        $size /= 1024;
    }

    return round($size, $precision).' '.$unit[$i];
}

?>