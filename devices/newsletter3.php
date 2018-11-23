<?php
require_once    ('config/PHPMailer-master/src/PHPMailer.php');
require_once    ('config/PHPMailer-master/src/SMTP.php');

$mail = new PHPMailer\PHPMailer\PHPMailer();
//$mail->IsSMTP(); // enable SMTP

/* $mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); // enable SMTP */

$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "le.reve.de.la.salamandre";
$mail->Password = "_storygraff2018";
$mail->SetFrom("le.reve.de.la.salamandre@gmail.com");
$mail->Subject = "Test without IsSmtp";
$mail->Body = "hello";
$mail->AddAddress("vincseize@gmail.com");

 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
 }


// https://help.adk-media.com/utiliser-classe-php-mailer-pour-envoi-emails-smtp.html
?>