<?php
error_reporting(E_ALL ^ E_STRICT);
// to turn error reporting off 
error_reporting(0);
$base = dirname(dirname(__FILE__));
require($base.'/devices/config/db.php');
$parent_folder = basename(dirname(__FILE__, 2));
$config = require($base.'/devices/config/config.php');
$name_site = $config['name_site'];


function reponseN($name_site){
    $reponseN = '<head>';
    $reponseN .= '<link rel="stylesheet" type="text/css" media="all" href="css/newsletter_inscription_style.css">';
    $reponseN .= '<link rel="stylesheet" type="text/css" media="all" href="css/newsletter_inscription_responsive.css">';
    $reponseN .= '<style type="text/css">';
        $reponseN .= '.roundedImage{';
            $reponseN .= 'overflow:hidden;';
            $reponseN .= '-webkit-border-radius:50px;';
            $reponseN .= '-moz-border-radius:50px;';
            $reponseN .= 'border-radius:50px;';
            $reponseN .= 'width:90px;';
            $reponseN .= 'height:90px;';
            $reponseN .= 'background-repeat: no-repeat;';
            $reponseN .= 'background-attachment: fixed;';
            $reponseN .= 'background:url(assets/images/icone.jpg);';
            $reponseN .= 'display: inline-block;';
        $reponseN .='}';

        $reponseN .= '.wrapper {';
            $reponseN .= 'text-align: center;';
        $reponseN .= '}';
    $reponseN .= '</style>';
    $reponseN .= '</head>';

    $reponseN .= '<body style="background-color:grey;overflow-y: hidden;">';
	$reponseN .= '<section id="container" style="padding-bottom:70px;">';
    $reponseN .= '<div class="wrapper">';
    $reponseN .= '<div class="roundedImage" style="background:url(../IMG/icone.jpg) no-repeat -40px -40px;">';
    $reponseN .= '&nbsp;';
    $reponseN .= '</div>';
    $reponseN .= '</div>';
    $reponseN .= '<h2 style="background-color:#E1E1E1;" align="center">Newsletter mensuelle Podcasts<br> '.strtoupper($name_site).'</h2>';
    $reponseN .= '<div id="wrappingX" class="clearfixX" align="center">';
    $reponseN .= '<h2><font color=grey>'.strtoupper($name_site).' vous remercie</font></h2>';
    $reponseN .= '<h3><font color=black>Votre demande a bien éte prise en compte.</font></h3>';
    $reponseN .= '<h3><font color=black>Vous allez recevoir un email de confirmation. (Eventuellement considérer comme Spam)</font></h3>';
    $reponseN .= '<h3><font color=black>Vous pouvez fermez cette page.</font></h3>';
    $reponseN .= '</section>';
    $reponseN .= '</div>';
    $reponseN .= '</body>';

    return $reponseN;
 
}

function messageDES($config,$parent_folder,$jeton){
    $url_confirm = $config['ip'].'/'.$parent_folder.'/devices/newsletter_confirm.php?token='.$jeton;
    $messageN = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>

    <div>
            <p>Clickez sur ce lien pour confirmer votre abonnement</p>
            <p><a href ="http://' . $url_confirm . '">[  Newsletter Et puis soudain ]</a></p>
    </div>
    </body>
    </html>';

    $messageN = stripslashes($messageN);
    return $messageN;
}

function confirm_by_mailDES($config,$email,$url_confirm,$messageN,$headerN){

    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email)) // On filtre les serveurs qui rencontrent des bogues.
    {
        $passage_ligne = "\r\n";
    }
    else
    {
        $passage_ligne = "\n";
    }
    //=====Déclaration des messages au format texte et au format HTML.
    $message_html = "<html><head></head><body><div>";
    $message_html .= "<p>Cliquez sur ce lien pour confirmer votre abonnement</p>";
    $message_html .= "<p>http://".$url_confirm ;
    $message_html .= "</p></div></body></html>";

    //==========
    
    //=====Création de la boundary
    $boundary = "-----=".md5(rand());
    //==========
    
    //=====Définition du sujet.
    $sujet = "Et puis soudain Confirmation Newsletter";
    //=========
    
    //=====Création du header de l'e-mail.
    $header = "From: \"Et puis soudain\"<le.reve.de.la.salamandre@mail.com>".$passage_ligne;
    //$header.= "Reply-to: \"WeaponsB\" <weaponsb@mail.fr>".$passage_ligne;
    $header.= "MIME-Version: 1.0".$passage_ligne;
    $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    //==========
    
    //=====Création du message.
    $message = $passage_ligne."--".$boundary.$passage_ligne;
    //=====Ajout du message au format texte.
/*     $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_txt.$passage_ligne; */
    //==========
    $message.= $passage_ligne."--".$boundary.$passage_ligne;
    //=====Ajout du message au format HTML
    $message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_html.$passage_ligne;
    //==========
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    //==========
    
    //=====Envoi de l'e-mail.
    mail($email,$sujet,$message,$header);
    //==========

}


if(isset($_POST['email']) AND !empty($_POST['email']))
{

    $mysqli = new mysqli($hostname, $username, $password, $database);
    mysqli_set_charset($mysqli, 'utf8'); 

    function jeton($length)
    {
        return substr( str_shuffle( str_repeat( 'abcdefghijklmnopqrstuvwxyz0123456789.', 10 ) ), 0, 25 );
    }

    $email = $_POST['email'];
    $date   = date('Y-m-d H:i:s');
    $statut = 'prepa';
    $jeton  = jeton(25); 
    $lang   = 'fr';

    $check_valid = 'false';
    $check_exist = 'false';

    $url_confirm = $config['ip'].'/'.$parent_folder.'/devices/newsletter_confirm.php?token='.$jeton;
    $postmaster = 'Postmaster '.$name_site;
    $expediteur = $config['expediteur'];

    $headerN = 'MIME-Version: 1.0'."\r\n";
    $headerN .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
    $headerN .= 'From: "'.$postmaster.'"<'.$expediteur.'>'."\r\n"; // Expediteur
    $subject = "Confirmation Abonnement Newsletter ".$name_site;

    $messageN = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    
    <div>
            <p>Clickez sur ce lien pour confirmer votre abonnement "<a href ="http://' . $url_confirm . '">[  Newsletter Et puis soudain ]</a>"</p>
    </div>
    </body>
    </html>';
    
    // echo $messageN;
    $messageN = stripslashes($messageN);

    $reponseN = reponseN($name_site);
    print_r($reponseN);

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $check_valid = 'ok';
    }else{
        echo "<font color=red>Email invalide!!!</font>";
    }
    $select = mysqli_query($mysqli, "SELECT email,jeton FROM spip_mailsubscribers WHERE email = '$email'") or exit(mysqli_error($mysqli));
    if(mysqli_num_rows($select)) {
        // echo "<font color=red>Email déja utilsé</font>";
        $check_exist = 'already';
    }else{
        $check_exist = 'ok';
    }

    if($check_valid == 'ok' AND $check_exist == 'ok')
    {
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        } 
        $sql = "INSERT INTO spip_mailsubscribers (email, statut, jeton, lang, date) 
        VALUES ('$email', '$statut', '$jeton', '$lang', '$date')";

        $url_confirm = $config['ip'].'/'.$parent_folder.'/devices/newsletter_confirm.php?token='.$jeton;

        if ($mysqli->query($sql) === TRUE) {
            
            // confirm_by_mail($config,$email,$url_confirm,$messageN,$headerN); -> OK

            if(mail($email,$subject,$messageN,$headerN)){
                //echo "";
                //echo 'Votre demande a bien été envoyé<br>';  // for debug
                return TRUE;
            }
            else{
                //echo "";  // for debug
                echo "Votre demande n'a pas pu aboutir (error1)";  // for debug
                return FALSE;
            }

            echo $reponseN;
            exit;

        } else {
            echo "";  // for debug
            echo "Votre demande n'a pas pu aboutir (error2)";  // for debug
            // echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
        $mysqli->close();
    }

/*     if($check_valid == 'ok' AND $check_exist == 'already')
    { */
            $sql = "SELECT email,jeton FROM spip_mailsubscribers WHERE email = '$email'";
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $jeton = $row["jeton"];

                    $messageN = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    </head>
                    <body>
                    
                    <div>
                            <p>Clickez sur ce lien pour confirmer votre abonnement "<a href ="http://' . $url_confirm . '">[  Newsletter Et puis soudain ]</a>"</p>
                    </div>
                    </body>
                    </html>';
                    
                    $messageN = stripslashes($messageN);

                    if(mail($email,$subject,$messageN,$headerN)){
                        //echo 'Votre demande a bien été envoyé<br>';  // for debug
                        return TRUE;
                    }
                    else{
                        echo "Votre demande n'a pas pu aboutir (error3)";  // for debug
                        //echo "";  // for debug
                        return FALSE;
                    }
        
                    echo $reponseN;
                    exit;

                }

            } else {
                echo "Votre demande n'a pas pu aboutir (error4)";  // for debug
                echo "0 results";
            }
            $mysqli->close();

/*     } */

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
.myButton {
	-moz-box-shadow:inset 0px 1px 0px 0px #54a3f7;
	-webkit-box-shadow:inset 0px 1px 0px 0px #54a3f7;
	box-shadow:inset 0px 1px 0px 0px #54a3f7;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3f85ab), color-stop(1, #2b495e));
	background:-moz-linear-gradient(top, #3f85ab 5%, #2b495e 100%);
	background:-webkit-linear-gradient(top, #3f85ab 5%, #2b495e 100%);
	background:-o-linear-gradient(top, #3f85ab 5%, #2b495e 100%);
	background:-ms-linear-gradient(top, #3f85ab 5%, #2b495e 100%);
	background:linear-gradient(to bottom, #3f85ab 5%, #2b495e 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3f85ab', endColorstr='#2b495e',GradientType=0);
	background-color:#3f85ab;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
	border:1px solid #50768f;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:13px;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #274161;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #2b495e), color-stop(1, #3f85ab));
	background:-moz-linear-gradient(top, #2b495e 5%, #3f85ab 100%);
	background:-webkit-linear-gradient(top, #2b495e 5%, #3f85ab 100%);
	background:-o-linear-gradient(top, #2b495e 5%, #3f85ab 100%);
	background:-ms-linear-gradient(top, #2b495e 5%, #3f85ab 100%);
	background:linear-gradient(to bottom, #2b495e 5%, #3f85ab 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#2b495e', endColorstr='#3f85ab',GradientType=0);
	background-color:#2b495e;
}
.myButton:active {
	position:relative;
	top:1px;
}
</style>

<body style="background-color:grey;overflow-y: hidden;">
	<section id="container" style="padding-bottom:70px;">
        <div class="wrapper">
            <div class="roundedImage" style="background:url(../IMG/icone.jpg) no-repeat -40px -40px;">
                &nbsp;
            </div>
        </div>
		<h2 style="background-color:#E1E1E1;">Newsletter mensuelle Podcasts<br> <?php echo strtoupper($name_site); ?></h2>
		<form name="hongkiat" id="hongkiat-form"  method="post" action="newsletter_inscription.php">
		<div id="wrapping" class="clearfix">
			<section id="aligned">
			<input type="email" name="email" id="email" placeholder="Votre e-mail" autocomplete="off" tabindex="2" class="txtinput">
			</section>
		</div>
		<section id="buttons">
			<input type="submit" name="submit" id="submitbtnDES" class="submitbtnDES myButton" tabindex="7" value="Envoyer">
			<br style="clear:both;">
        </section>
        <section id="recipientcase">
            <h3>* Vous recevrez un mail de confirmation<i></h3>
            <br style="clear:both;">
        </section>
		</form>
	</section>
</body>

</html>