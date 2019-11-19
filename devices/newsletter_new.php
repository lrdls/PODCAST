<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
/////////////////////////// DONT TOUCH HERE -> 'config/config.php'
$base = dirname(dirname(__FILE__));

require($base.'/devices/config/db.php');
$config = require($base.'/devices/config/config.php');

require_once($base.'/devices/config/PHPMailer-master/src/PHPMailer.php');
require_once($base.'/devices/config/PHPMailer-master/src/SMTP.php');
$mail = new PHPMailer\PHPMailer\PHPMailer();
/*     echo 'm'; */
    // $mail = new PHPMailer\PHPMailer\PHPMailer();
    //$mail->IsSMTP(); // enable SMTP

    /* $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP(); // enable SMTP */

    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    // $mail->Username = "le.reve.de.la.salamandre";
/*     $mail->Username = $username;
    $mail->Password = "_storygraff2018"; */
    // $mail->SetFrom("le.reve.de.la.salamandre@gmail.com");














$parent_folder = end(explode('/',$base)); 



$expediteur = $config['expediteur'];
//echo $expediteur;
$ip = $config['ip'];
$name_site = $config['name_site'];
$titleNewsletter = $config['titleNewsletter'];
$copyright2 = '';
$copyright2 = $config['copyright2'];
$mailsAuthors_to_exclude = $config['mailsAuthors_to_exclude'];
$delay_sendmail = $config['delay_sendmail'];

// var newsletter
$url_www = 'http://'.$ip.'/'.$parent_folder;
//$url_www_spip = 'http://'.$ip;
// $dateCheck = date("Y-m"); // news du mois courant
$dateCheck = date('Y-m', strtotime('-1 month')); // news du mois precedent
// $dateCheck = "2018-10"; // for debug
$dateNow = $dateCheck;
$copyright1 = strtoupper($name_site);
$title_site = 'Newsletter | '.$name_site;
$postmaster = 'Postmaster '.$name_site;
$extensions_icones = ['.jpg','.png','.gif'];
$link_desabo = $url_www.'/devices/desabo.php';

$mysqli = new mysqli($hostname, $username, $password, $database);
mysqli_set_charset($mysqli, 'utf8');

// Get Data from Bdd
$query = "SELECT id_article,surtitre,titre,soustitre,id_rubrique,descriptif,texte,date,date_modif,statut,maj FROM spip_articles WHERE statut='publie'";
$results_articles = array();
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        if(substr($row['date'],0,7)==$dateCheck){
            $results_articles[] = $row;
        }
    }
    $result->free();
}

$query = "SELECT id_rubrique,titre,statut FROM spip_rubriques WHERE statut='publie'";
$results_rubriques = array();
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $results_rubriques[] = $row;
    }
    $result->free();
}

$query = "SELECT email,jeton FROM spip_mailsubscribers WHERE statut='valide'";
$results_mailsubscribers = array();
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $results_mailsubscribers[] = $row;
    }
    $result->free();
}

$query = 'SELECT email FROM spip_auteurs';
$results_authors = array();
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $results_authors[] = $row;
    }
    $result->free();
}

$mysqli->close();


// Data from Functions
$n_articles = count($results_articles);

$results_rubriques = json_encode($results_rubriques);
$results_articles = json_encode($results_articles);
$results_mailsubscribers = json_encode($results_mailsubscribers);
$results_authors = json_encode($results_authors);

$arr_articles = json_decode($results_articles, true);
$arr_rubriques = json_decode($results_rubriques, true);
$arr_mailsubscribers = json_decode($results_mailsubscribers, true);
$arr_authors = json_decode($results_authors, true);

///////////////////////////////////////////////////////////////////////////////////




















//function PHPMailer($postmaster, $username, $mail){
function fct_PHPMailer($postmaster, $username, $mailing_list){

    $mail->SetFrom($username);
    $mail->Subject = "Test Floyd2";
    $mail->Body = "hello";
    //$mail->AddAddress($mail);


    foreach($mailing_list as $mail) {
        echo $mail;
        $mail->AddAddress($mail);
    }








    // $mail->AddAddress("vincseize@gmail.com"); 

/*     foreach($ar_mail as $mail) {
        $mailing_list[] = $mailsubscriber['email'];

    } */

/*     if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message has been sent";
    } */

}

function get_mailing_list($arr_mailsubscribers,$arr_authors,$mailsAuthors_to_exclude){
    $mailing_list = array();
    foreach($arr_mailsubscribers as $mailsubscriber) {
        $mailing_list[] = $mailsubscriber['email'];
    }
    foreach($arr_authors as $author) {
            $mailing_list[] = $author['email'];
    }
    $mailing_list = array_diff(array_unique($mailing_list), $mailsAuthors_to_exclude );
    return $mailing_list;
}

function headers($postmaster,$expediteur){
    $headerN = 'MIME-Version: 1.0'."\r\n";
    $headerN .= 'Content-type: text/html; charset=UTF-8'."\r\n";
    $headerN .= 'From: '.$postmaster.'<'.$expediteur.'>'."\r\n"; // Expediteur
    echo $expediteur;
    return $headerN;
}

function sendmail($to,$subject,$messageN,$headerN,$delay_sendmail){
    //sleep($delay_sendmail);
    echo $to;
    echo $subject;
    echo $headerN;
    $messageN = "";
    $headerN = "";
    if(mail($to,$subject,$messageN,$headerN)){
        
        echo 'sendmail Votre message a bien été envoyé<br>';  // for debug
        return TRUE;
    }
    else{
        
        echo "sendmail Votre message n'a pas pu être envoyé";  // for debug
        return FALSE;
    }
}

function mailing_list($to,$subject,$messageN,$headerN,$delay_sendmail){
    //sleep($delay_sendmail);
    echo $to;
    if(mail($to,$subject,$messageN,$headerN)){
        echo 'mailing_list Votre message a bien été envoyé<br>'; // for debug
        return TRUE;
    }
    else{
        echo "mailing_list Votre message n'a pas pu être envoyé";  // for debug
        return FALSE;
    }
}

function searchJeton($mail, $array) {
    foreach ($array as $key => $val) {
/*         print_r($val);
        print_r("<br>"); */
        if ($val['email'] === $mail) {
            return $val['jeton'];
        }
    }
    return null;
 }


 function add_footer($copyright1,$copyright2,$desabo,$display){
    $messageN .= '      <tr>';
    $messageN .= '        <td class="footer" bgcolor="#44525f">';
    //footer
    $messageN .= '          <table width="100%" border="0" cellspacing="0" cellpadding="0">';
    $messageN .= '          <tr>';
    $messageN .= '            <td align="center" class="footercopy">';
    $messageN .= '              <font color="black">&copy; '.$copyright1.' - '.$copyright2.' &nbsp; | &nbsp; 2017 - '.date("Y").'</font>';
    $messageN .= '        <div style="'.$display.'">';
    $messageN .= '              <span><font color="#8da9c4"><a href="'.$desabo.'" target="_blank" class="unsubscribe">';
    $messageN .= '                  <font color="#8da9c4">Se désabonner</font></a>';
    $messageN .= '              </span>';
    $messageN .= '              <span class="hide"><font color="#8da9c4">de la newsletter</font></span>';

    $messageN .= '              <span><font color="#8da9c4"><a href="http://etpuissoudain.com" target="_blank" class="unsubscribe">';
    $messageN .= '                  <font color="#8da9c4">Site</font></a>';
    $messageN .= '              </span>';


    $messageN .= '        </div>';
    $messageN .= '            </td>';
    $messageN .= '          </tr>';
    $messageN .= '         </table>';
    
    $messageN .= '       </td>';
    $messageN .= '     </tr>';
    $messageN .= '     </table>';
    
    $messageN .= '     <!--[if (gte mso 9)|(IE)]>';
    $messageN .= ' </td>';
    $messageN .= ' </tr>';
    $messageN .= ' </table>';
    $messageN .= ' <![endif]-->';
    
    $messageN .= '   </td>';
    $messageN .= ' </tr>';
    $messageN .= ' </table>';
    
    $messageN .= '</body>';

    return $messageN;
}





//////////////////////////////////////// construct mails ///////////////////////////////////////////////
//PHPMailer($postmaster, $expediteur);


$mailing_list = get_mailing_list($arr_mailsubscribers,$arr_authors,$mailsAuthors_to_exclude);
$n_subscribers = count($mailing_list);
$headerN = headers($postmaster,$expediteur);
$subject = $titleNewsletter;

foreach($mailing_list as $mail) {
    $jeton = searchJeton($mail, $arr_mailsubscribers);
    // $jeton = '7545105435be1e3a560de33.4'; // for debug
    $desabo = $link_desabo.'?token='.$jeton;
    if (empty($jeton)) {
        $display = 'display:none';
        $desabo = '';
      }
    $message = $messageN;
    $footerN = add_footer($copyright1,$copyright2,$desabo,$display);
    $message .= $footerN;

    sleep($delay_sendmail);
    $chckMail_validate_sended = 'FALSE';
    $chckMail_validate_filter = 'FALSE';
    if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $chckMail_validate_filter = 'TRUE';
    }
    $result = mail($mail,$subject,$message,$headerN);
    if(!$result) {   
        $chckMail_validate_sended = 'FALSE';
    } else {
        $chckMail_validate_sended = 'TRUE';
    }
    if($chckMail_validate_filter  == 'FALSE' || $chckMail_validate_sended == 'FALSE'){
        $mail_admin = 'vincseize@gmail.com';
        $mail = '<b>Mail:</b><br/>'.$mail."\r\n";
        $send = sendmail($mail_admin,'Mail error',$mail,$headerN,$delay_sendmail); 
    }
}





/* print_r($mailing_list);

foreach($mailing_list as $mail) {
    echo '<br>--$mail--<br>';
    echo '<br>--'.$mail.'--<br>';
    echo '<br>--$expediteur--<br>';
    echo '<br>--'.$expediteur.'--<br>';
    echo '<br>--$posmaster--<br>';
    echo '<br>--'.$postmaster.'--<br>';
PHPMailer($postmaster, $expediteur, $mail);

} */

fct_PHPMailer($postmaster, $expediteur, $mailing_list);
// mailing_list










// https://help.adk-media.com/utiliser-classe-php-mailer-pour-envoi-emails-smtp.html
?>