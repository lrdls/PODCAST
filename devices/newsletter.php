<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
/////////////////////////// DONT TOUCH HERE -> 'config/config.php'
$base = dirname(dirname(__FILE__));
require($base.'/devices/config/db.php');
$config = require($base.'/devices/config/config.php');
$parent_folder = end(explode('/',$base)); 
$expediteur = $config['expediteur'];
$ip = $config['ip'];
$name_site = $config['name_site'];
$titleNewsletter = $config['titleNewsletter'];
$copyright2 = '';
$copyright2 = $config['copyright2'];
$mailsAuthors_to_exclude = $config['mailsAuthors_to_exclude'];
$delay_sendmail = $config['delay_sendmail'];

// var newsletter
$url_www = 'http://'.$ip.'/'.$parent_folder;
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

///////////////////////////////////////////////////////////////////////////////////////////// functions
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

    /* 
    // TESTS all possible differentes entêtes, UNSTABLE
    //$to=array();
    //$BCC = 'vincseize@gmail.com,roropot72@gmail.com';
    $BCC = implode(",", $to);
    $entetedate  = date("D, j M Y H:i:s -0600"); // Offset horaire
    $destinataire = 'vincseize@gmail.com';
    // Pour les champs $expediteur / $copie / $destinataire
    //séparer par une virgule s'il y a plusieurs adresses
    $expediteur = 'le.reve.de.la.salamandre@gmail.com';
    // $copie = 'vincseize@gmail.com';
    // $copie_cachee = 'vincseize@gmail.com';
    $objet = 'NEWSLETTER Et puis soudain'; // Objet du message
    $headerT  = 'MIME-Version: 1.0' . "\r\n"; // Version MIME
    $headerT .= 'Content-type: text/html; charset=UTF-8'."\r\n"; // en-tete format HTML
    // $headerT .= "Content-Type: text/html; charset=UTF-8\r\n";
    // $headerT .= 'Reply-To: '.$expediteur."\r\n"; // Mail de reponse
    $headerT .='X-Mailer: PHP/' . phpversion();
    $headerT .= 'Date: '.$entetedate."\r\n"; // Mail de reponse
    $headerT .= 'List-Unsubscribe: <http://etpuissoudain.com/unsubscribe?user=2>'."\n"; // unsubscribe
    $headerT .= 'From: "'.$postmaster.'"<'.$expediteur.'>'."\r\n"; // Expediteur
    $headerT .= 'Reply-To: "'.$postmaster.'"<'.$expediteur.'>'."\r\n"; // Expediteur
    $headerT .= 'Delivered-to: '.$destinataire."\r\n"; // Destinataire
    // $headerT .= 'Cc: '.$copie."\r\n"; // Copie Cc
    // $headerT .= 'Bcc: '.$copie_cachee."\r\n"; // Copie cachée Bcc        
    // $headerT .= 'Bcc: '. $BCC . "\r\n";
    $headerT .= 'Bcc: roropot72@gmail.com' . "\r\n"; 
    */

    /*  
    // TESTS2 entêtes, STABLE
    $headerN = "Reply-To: The Sender <le.reve.de.la.salamandre@gmail.com>\r\n"; 
    $headerN .= "Return-Path: The Sender <le.reve.de.la.salamandre@gmail.com>\r\n";
    $headerN .= "From: The Sender <le.reve.de.la.salamandre@gmail.com>\r\n"; 
    $headerN .= "Organization: Sender Organization\r\n";
    $headerN .= "MIME-Version: 1.0\r\n";
    $headerN .= "Content-type: text/plain; charset=iso-8859-1\r\n";
    $headerN .= "X-Priority: 3\r\n";
    $headerN .= "X-Mailer: PHP". phpversion() ."\r\n" 
    mail("roropot72@gmail.com", "Message", "A simple message.", $headerN); 
    */
    $headerN = 'MIME-Version: 1.0'."\r\n";
    $headerN .= 'Content-type: text/html; charset=UTF-8'."\r\n";
    $headerN .= 'From: "'.$postmaster.'"<'.$expediteur.'>'."\r\n"; // Expediteur
    /* $subject = $titleNewsletter; */

    return $headerN;

}

function sendmail($to,$subject,$messageN,$headerN,$delay_sendmail){
    //sleep($delay_sendmail);
    if(mail($to,$subject,$messageN,$headerN)){
        //echo 'sendmail Votre message a bien été envoyé<br>';  // for debug
        return TRUE;
    }
    else{
        //echo "sendmail Votre message n'a pas pu être envoyé";  // for debug
        return FALSE;
    }
}

function mailing_list($to,$subject,$messageN,$headerN,$delay_sendmail){
    //sleep($delay_sendmail);
    if(mail($to,$subject,$messageN,$headerN)){
        //echo 'mailing_list Votre message a bien été envoyé<br>'; // for debug
        return TRUE;
    }
    else{
        //echo "Votre message n'a pas pu être envoyé";  // for debug
        return FALSE;
    }
}

function searchJeton($mail, $array) {
    foreach ($array as $key => $val) {
        if ($val['email'] === $mail) {
            return $val['jeton'];
        }
    }
    return null;
 }

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

// head, css
$messageN = '<head>';
$messageN .= '<title>'.$title_site.'</title>';
// cs
$messageN .= '<style type="text/css">';
$messageN .= 'body {margin: 0; padding: 0; min-width: 100%!important;}';
$messageN .= 'img {height: auto;}';
$messageN .= '.content {width: 100%; max-width: 600px;}';
$messageN .= '.header {padding: 40px 30px 20px 30px;padding-bottom:0;padding-top:0;}';
$messageN .= '.innerpadding {padding: 30px 30px 30px 30px;}';
$messageN .= '.borderbottom {border-bottom: 1px solid #f2eeed;}';
$messageN .= '.subhead {font-size: 12px; line-height: 100%; color: #ffffff; font-family: sans-serif; letter-spacing: 10px;}';
$messageN .= '.h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}';
$messageN .= '.h1 {font-size: 22px; line-height: 100%; font-weight: bold;}';
$messageN .= '.h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}';
$messageN .= '.bodycopy {font-size: 16px; line-height: 22px;}';
$messageN .= '.button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}';
$messageN .= '.button a {color: #ffffff; text-decoration: none;}';
$messageN .= '.footer {padding: 20px 30px 15px 30px;}';
$messageN .= '.footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}';
$messageN .= '.footercopy a {color: #ffffff; text-decoration: underline;}';
$messageN .= ' @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {';
$messageN .= 'body[yahoo] .hide {display: none!important;}';
$messageN .= 'body[yahoo] .buttonwrapper {background-color: transparent!important;}';
$messageN .= 'body[yahoo] .button {padding: 0px!important;}';
$messageN .= 'body[yahoo] .button a {background-color: #effb41; padding: 15px 15px 13px!important;}';
$messageN .= 'body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}';
$messageN .= '  }';
$messageN .= '  /*@media only screen and (min-device-width: 601px) {';
$messageN .= '  .content {width: 600px !important;}';
$messageN .= '  .col425 {width: 425px!important;}';
$messageN .= '  .col380 {width: 380px!important;}';
$messageN .= '  }*/';
$messageN .= '  /* ';
$messageN .= '  ##Device = Tablets, Ipads (portrait) ';
$messageN .= '  ##Screen = B/w 768px to 1024px ';
$messageN .= '  */ ';
$messageN .= '  @media (min-width: 768px) and (max-width: 1024px) {';
$messageN .= '  h1 {font-size: 24px; line-height: 38px; font-weight: bold;}';
$messageN .= '  }';
$messageN .= '  </style>';
//
$messageN .= '  </head>';

// body
$messageN .= '<body yahoo bgcolor="#ffffff">';
// bandeau top
$messageN .= '<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">';
$messageN .= '<tr>';
$messageN .= '<td>';

$messageN .= '<!--[if (gte mso 9)|(IE)]>';
$messageN .= '<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">';
$messageN .= '<tr>';
$messageN .= '<td>';
$messageN .= '<![endif]-->';

$messageN .= '<table height="70" bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">';
$messageN .= '<tr>';
$messageN .= '<td bgcolor="#00707B" class="header">';
$messageN .= '<table widthX="70" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">';
$messageN .= '<tr>';
$messageN .= '<td height="70" width="70" style="padding: 0 20px 20px 0;padding-bottom:0;">';
$messageN .= '<img class="fix" src="'.$url_www.'/IMG/siteon0.png" width="70" height="70" border="0" alt="icone site"/>';
$messageN .= '</td>';
$messageN .= '<td>';

$messageN .= '          <!--[if (gte mso 9)|(IE)]>';
$messageN .= '            <table widthX="425" align="left" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">';
$messageN .= '            <tr>';
$messageN .= '            <td>';
$messageN .= '            <![endif]-->';
$messageN .= '          <table classX="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">';
$messageN .= '          <tr>';
$messageN .= '            <td height="70">';
$messageN .= '              <table widthX="100%" border="0" cellspacing="0" cellpadding="0" style="width: 100%;">';
$messageN .= '              <tr>';
$messageN .= '                <td class="subhead" style="padding: 0 0 0 3px;">';
$messageN .= '                   ET PUIS SOUDAIN';
$messageN .= '                </td>';
$messageN .= '              </tr>';
$messageN .= '              <tr>';
$messageN .= '                <td class="h1 " style="padding: 5px 0 0 0;">';
$messageN .= '                   NEWSLETTER '.$dateNow;
$messageN .= '                </td>';
$messageN .= '              </tr>';
$messageN .= '              </table>';
$messageN .= '            </td>';
$messageN .= '          </tr>';
$messageN .= '          </table>';


$messageN .= '</td>';
$messageN .= '</tr>';
$messageN .= '</table>';

$messageN .= '          <!--[if (gte mso 9)|(IE)]>';
$messageN .= '            </td>';
$messageN .= '            </tr>';
$messageN .= '            </table>';
$messageN .= '            <![endif]-->';

$messageN .= '        </td>';
$messageN .= '      </tr>';
$messageN .= '      <tr>';
$messageN .= '        <td class="innerpadding borderbottom">';
$messageN .= '          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:0;padding-bottom:0;">';

// titre
$messageN .= '          <tr>';
$messageN .= '            <td class="h2" style="margin-bottom:0;padding-bottom:0;">';
$messageN .= '               '.$titleNewsletter.' '.'<font color="grey">[ '.$n_articles.' ]</font>';
$messageN .= '            </td>';
$messageN .= '          </tr>';
$messageN .= '          </table>';
$messageN .= '        </td>';
$messageN .= '      </tr>';

// boucle podcasts
foreach($arr_articles as $article) {
    $id_article = $article['id_article'];
    // $url_podcast = $url_www.'/spip.php?page=article&id_article='.$id_article;
    $url_podcast = $url_www.'/devices/index.php?page=article&id_article='.$id_article;
    $id_secteur = $article['id_secteur'];
    $categorie = '';

    foreach($arr_rubriques as $rubrique) {
        $id_rubrique = $rubrique['id_rubrique'];
        if($id_rubrique == $id_secteur){$categorie = $rubrique['titre'];}
    }

    $date = substr($article['date'],0,7);
    $title = strtoupper($article['titre']);
    $duree = $article['maj'];
    $auteur = '';
	$url_icon = "";

	foreach ($extensions_icones as $ext) {
		$url_icon = $url_www.'/IMG/arton'.$id_article.$ext;
		$file_headers = @get_headers($url_icon);
		if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
		    $exists = false;
		}
		else {
		    $exists = true;
		}
		if($exists==true){break;}
	}

    $chapo = $article['chapo'];
    $resume = '';
    if($article['descriptif'] != ''){$resume = 'Résumé : ...'.$article['descriptif'];}

    if($date==$dateCheck){
        $messageN .= '      <tr>';
        $messageN .= '        <td class="innerpadding borderbottom">';
        $messageN .= '          <table width="115" align="left" border="0" cellpadding="0" cellspacing="0">';
        $messageN .= '          <tr>';
        $messageN .= '            <td height="115" style="padding: 0 20px 20px 0;">';
        $messageN .= '              <img class="fix" src="'.$url_icon.'" width="115" height="115" border="0" alt="icone article"/>';
        $messageN .= '            </td>';
        $messageN .= '          </tr>';
        $messageN .= '          </table>';

        $messageN .= '          <!--[if (gte mso 9)|(IE)]>';
        $messageN .= '            <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">';
        $messageN .= '            <tr>';
        $messageN .= '            <td>';
        $messageN .= '            <![endif]-->';

        $messageN .= '          <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 380px;">';
        $messageN .= '          <tr>';
        $messageN .= '            <td>';
        $messageN .= '              <table width="100%" border="0" cellspacing="0" cellpadding="0">';
        $messageN .= '              <tr>';
        $messageN .= '                <td class="bodycopy">';
        $messageN .= '                <b>'.$title.'</b><br/><i>'.$categorie.'</i>  <a href="'.$url_podcast.'" target="_blank">[ play podcast ]</a>';
        $messageN .= '                <br/><br/>'.$resume.'';
        /*   $messageN .= '                <br/>'.$chapo; */
        $messageN .= '                </td>';
        $messageN .= '              </tr>';
        $messageN .= '              </table>';
        $messageN .= '            </td>';
        $messageN .= '          </tr>';
        $messageN .= '          </table>';

        $messageN .= '          <!--[if (gte mso 9)|(IE)]>';
        $messageN .= '            </td>';
        $messageN .= '            </tr>';
        $messageN .= '            </table>';
        $messageN .= '            <![endif]-->';

        $messageN .= '        </td>';
        $messageN .= '      </tr>';
    }

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
    $messageN .= '              <font color="#8da9c4">Se désabonner</font></a></span>';
    $messageN .= '              <span class="hide"><font color="#8da9c4">de la newsletter</font></span>';
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


echo $message; // for debug

?>