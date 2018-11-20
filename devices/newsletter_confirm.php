<?php
error_reporting(E_ALL ^ E_STRICT);
error_reporting(0);

if(isset($_GET['token']) AND !empty($_GET['token'])){
    $base = dirname(dirname(__FILE__));
    require($base.'/devices/config/db.php');

    $jeton = $_GET['token'];

    $mysqli = mysqli_connect($hostname, $username, $password,$database);
   
    if(! $mysqli ) {
        echo "Votre demande n'a pas pu aboutir, veuillez nous excuser!"; 
       die('Could not connect: ' . mysqli_error());
    }
    // echo 'Connected successfully<br>';
    $sql = "UPDATE spip_mailsubscribers SET statut='valide' WHERE jeton='$jeton'";
    
    if (mysqli_query($mysqli, $sql)) {
       echo "Inscription confirmée.";
    } else {
        echo "Votre demande n'a pas pu aboutir, veuillez nous excuser!"; 
       // echo "Error updating record: " . mysqli_error($mysqli);
    }
    mysqli_close($mysqli);

}