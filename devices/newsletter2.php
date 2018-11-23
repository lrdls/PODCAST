<?php

/* Namespace alias. */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* Include the Composer generated autoload.php file. */
//require 'C:\xampp\composer\vendor\autoload.php';
require_once('config/PHPmailer/vendor/autoload.php');

$mail = new PHPmailer(TRUE);


/* Open the try/catch block. */
try {

    $mail->IsSMTP();
    $mail->Host = "smtp.gmail.com";
    
    // optional
    // used only when SMTP requires authentication  
    $mail->SMTPAuth = true;
    $mail->Username = 'lrdls';
    $mail->Password = 'storygraff2018';

$mail->setFrom('vincseize@gmail.com'); // Personnaliser l'envoyeur
$mail->addAddress('vincseize@gmail.com'); // Ajouter le destinataire
$mail->addAddress('vincseize@gmail.com'); 

$mail->isHTML(true); // Paramétrer le format des emails en HTML ou non

$mail->Subject = 'Here is the subject';
$mail->Body = 'This is the HTML message body';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//$mail->SMTPDebug = 1;

$mail->send();

/*     if(!$mail->send()) {
        echo 'Erreur, message non envoyé.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Le message a bien été envoyé !';
    }
 */

}
catch (Exception $e)
{
   /* PHPMailer exception. */
   echo $e->errorMessage();
}
catch (\Exception $e)
{
   /* PHP exception (note the backslash to select the global namespace Exception class). */
   echo $e->getMessage();
}











exit;

?>