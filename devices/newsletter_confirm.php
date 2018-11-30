<?php
error_reporting(E_ALL ^ E_STRICT);
error_reporting(0);
$base = dirname(dirname(__FILE__));
$config = require($base.'/devices/config/config.php');
$name_site = $config['name_site'];
$hidden_ok = "none";
$hidden_error1 = "block";
$hidden_error2 = "block";

if(isset($_GET['token']) AND !empty($_GET['token']))
{
    $base = dirname(dirname(__FILE__));
    require($base.'/devices/config/db.php');

    $jeton = $_GET['token'];

    $mysqli = mysqli_connect($hostname, $username, $password,$database);
   
    if(! $mysqli ) {
        $hidden_error1 = "block";
        //echo "Votre demande n'a pas pu aboutir, veuillez nous excuser! (error1)"; 
       die('Could not connect: ' . mysqli_error());
    }
    // echo 'Connected successfully<br>';
    $sql = "UPDATE spip_mailsubscribers SET statut='valide' WHERE jeton='$jeton'";
    
    if (mysqli_query($mysqli, $sql)) {
        $hidden_ok = "block";
        $hidden_error1 = "none";
        $hidden_error2 = "none";
       //echo "Inscription confirmée.";
    } else {
        $hidden_error2 = "block";
        //echo "Votre demande n'a pas pu aboutir, veuillez nous excuser! (error2)"; 
       // echo "Error updating record: " . mysqli_error($mysqli);
    }
    mysqli_close($mysqli);
}

?>


<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title><?php echo $name_site; ?></title>
<!--   <link rel="shortcut icon" href="favicon.ico">
  <link rel="icon" href="favicon.ico"> -->
  <link rel="stylesheet" type="text/css" media="all" href="assets/css/newsletter_inscription_style.css">
  <link rel="stylesheet" type="text/css" media="all" href="assets/css/newsletter_inscription_responsive.css">
</head>

<style type="text/css">
.roundedImage{
    overflow:hidden;
    -webkit-border-radius:50px;
    -moz-border-radius:50px;
    border-radius:50px;
    width:90px;
    height:90px;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background:url(assets/images/icone.jpg);
    display: inline-block;
}
.wrapper {
  text-align: center;
}
</style>

    <body style="background-color:grey;overflow-y: hidden;">
	<section id="container" style="padding-bottom:70px;">
    <div class="wrapper">';
    <div class="roundedImage" style="background:url(../IMG/icone.jpg) no-repeat -40px -40px;">
    &nbsp;
    </div>
    </div>
    <h2 style="background-color:#E1E1E1;" align="center">Newsletter mensuelle Podcasts <?php echo strtoupper($name_site); ?></h2>

    <div id="wrappingX" class="clearfixX" align="center">

    <h3 style="display:<?php echo $hidden_ok; ?>;"><font color=grey>Inscription confirmée. <?php echo strtoupper($name_site); ?> vous remercie</font></h2>
    <h3 style="display:<?php echo $hidden_error1; ?>;"><font color=black>Votre demande n'a pas pu aboutir, veuillez nous excuser! (error1)</font></h3>
    <h3 style="display:<?php echo $hidden_error2; ?>;"><font color=black>Votre demande n'a pas pu aboutir, veuillez nous excuser! (error2)</font></h3>

    </section>
    </div>

    </body>