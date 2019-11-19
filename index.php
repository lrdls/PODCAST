<?php
error_reporting(E_ALL ^ E_STRICT);
// to turn error reporting off 
error_reporting(0);

require('devices/config/db.php');
require('devices/config/fcts.php');
$maxFileSize = getMaximumFileUploadSize();
$maxFileSize = formatBytes($maxFileSize, 2);
define("MAX_UPLOAD_SIZE",$maxFileSize);
//echo constant("MAX_UPLOAD_SIZE");

$mysqli = new mysqli($hostname, $username, $password, $database);
mysqli_set_charset($mysqli, 'utf8'); 

$keywords_rubriques = [];
$sql = "SELECT * FROM spip_rubriques WHERE statut='publie'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		//echo $row["titre"];
		$keywords_rubriques[] = $row['titre'];
	}
}
$keywords_articles = [];
$sql = "SELECT * FROM spip_articles WHERE statut='publie'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		//echo $row["titre"];
		$keywords_articles[] = $row['titre'];
	}
}
$list_keyword_rubriques = implode(", ",$keywords_rubriques);
$list_keyword_articles = implode(", ",$keywords_articles);
?>


<meta charset="UTF -8">
<meta http-equiv="X-UA -Compatible" content="IE=edge">
<meta name="viewport" content="width=device -width , initial -scale =1">
<meta name="Content-Type" content="UTF-8">
<meta name="Content-Language" content="fr">
<meta name="Copyright" content="LRDS">
<meta name="Author" content="Vincseize, Charles POTTIER, J. Néret">
<meta name="Publisher" content="LRDS">
<meta name="Revisit-After" content="15 days">
<meta name="Robots" content="all">
<meta name="Rating" content="general">
<meta name="Distribution" content="global">
<meta name="Category" content="histoire">
<meta name="description" content="le podcast dans lequel on se raconte des histoires">

<meta name="keywords" content="podcast, et puis soudain, etpuissoudain, anecdote, anecdotes, histoire, histoires">

<meta name="keywords" content="<?php echo $list_keyword_rubriques;?>">
<meta name="keywords" content="<?php echo $list_keyword_articles;?>">

<meta name="generator" content="Spip">
<meta name="generator" content="Codeigniter">
<meta name="generator" content="Php">

<?php

header('Location: devices/index.php');
exit;

	# appel SPIP
	//include('spip.php');
	

/* echo('Check');
echo("<br>"); */

$newURL = 'index_redirect.php';

// Include and instantiate the class.
require_once 'libs/Mobile_Detect.php';
$detect = new Mobile_Detect;
 
// Any mobile device (phones or tablets).
if ( $detect->isMobile() ) {
	echo('Mobile');
	header('Location: '.$newURL);
	exit;
}
 
// Any tablet device.
if( $detect->isTablet() ){
	echo('Tablette');
	header('Location: '.$newURL);
	exit;
}
 
// Exclude tablets.
if( $detect->isMobile() && !$detect->isTablet() ){
	echo('Exclude tablets');
	//header('Location: '.$newURL);
	exit;
}
 
// Check for a specific platform with the help of the magic methods:
if( $detect->isiOS() ){
	echo('isiOS');
	header('Location: '.$newURL);
	exit;
}
 
if( $detect->isAndroidOS() ){
	echo('isAndroidOS');
	header('Location: '.$newURL);
	exit;
}
 
// Alternative method is() for checking specific properties.
// WARNING: this method is in BETA, some keyword properties will change in the future.
//$detect->is('Chrome')
//$detect->is('iOS')
//$detect->is('UC Browser')
// [...]
 
// Batch mode using setUserAgent():
$userAgents = array(
'Mozilla/5.0 (Linux; Android 4.0.4; Desire HD Build/IMM76D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19',
'BlackBerry7100i/4.1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/103',
// [...]
);
foreach($userAgents as $userAgent){
 
  $detect->setUserAgent($userAgent);
  $isMobile = $detect->isMobile();
  $isTablet = $detect->isTablet();
  // Use the force however you want.
 
}
 
// Get the version() of components.
// WARNING: this method is in BETA, some keyword properties will change in the future.
//$detect->version('iPad'); // 4.3 (float)
//$detect->version('iPhone') // 3.1 (float)
//$detect->version('Android'); // 2.1 (float)
//$detect->version('Opera Mini'); // 5.0 (float)

# appel SPIP
/* echo("<br>");
echo($isMobile);
echo("<br>");
echo($isTablet); */
include('spip.php');
//header('Location: '.$newURL);