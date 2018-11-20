<?php

$display_general = "none";

if (isset($_GET['token'])  AND !empty($_GET['token'])){
    $base = dirname(dirname(__FILE__));
    require($base.'/devices/config/db.php');
    $config = require($base.'/devices/config/config.php');

    $text_desabo_ok = $config['text_desabo_ok'];
    $expediteur = $config['expediteur'];

/*     $hostname = $config['dbLocation'];
    $username = $config['dbUser'];
    $password = $config['dbPassword'];
    $database = $config['dbName'];  */

    $display_general = "block";
    $text_desabo_false = 'Un problème empêche d\'executer cette requête, ';
    $text_desabo_false .= '<br/>veuillez nous envoyer un ';

    $subject = 'eps%20newsletter%20unsubscribe%20error';
    $text_desabo_false .= '<a onClick="javascript:';
    $text_desabo_false .= 'window.open(\'mailto:'.$expediteur.'?subject='.$subject.'\', \'Mail\');event.preventDefault()"  ';
    $text_desabo_false .= 'href=\'mailto:'.$expediteur.'?';
    $text_desabo_false .= 'subject='.$subject.'\'>mail</a> ';
    $text_desabo_false .= 'afin de procéder à votre requête';

    $token = $_GET['token'];
    // $token = 'aaa'; // debug

    try {
        $dbo = new PDO('mysql:host='.$hostname.';dbname='.$database, $username, $password);
        } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $reponse = $text_desabo_false;
        $display = 'none';
        die();
    }

    $stmt=$dbo->prepare("DELETE FROM spip_mailsubscribers WHERE jeton=:jeton");
    $stmt->bindParam(":jeton",$token,PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->execute()) { 
        $reponse = $text_desabo_ok;
        $display = 'block';
    }
    else {

        $reponse = $text_desabo_false;
        $display = 'none';
    }

}

// https://www.plus2net.com/php_tutorial/pdo-delete.php
// 
?>

<!DOCTYPE html">
<head><meta http-equiv="Content-Type" content="Content-type: text/html; charset=UTF-8" /><meta name="robots" content="noindex" /><title>
	Unscubscribe
</title>
<style>
body{background:#f2f2f2;font-family:Arial,Helvetica,sans-serif;padding-top:65px;text-align:center}
a,a:hover{color:#185787}
p{margin:0;padding:0 0 13px}
.container{background:#fff;border:1px solid #ddd;border-radius:6px;max-width:580px;margin:0 auto;padding:34px 0 24px;width:100%}
.instructions,.title{padding:0 39px;text-align:left}
.instructions h2,.instructions h3,.title h2,.title h3{color:#262626;font-size:30px;font-weight:400;letter-spacing:0;margin:0 0 13px;padding:0}
.instructions h3,.title h3{font-size:22px}
.instructions p,.title p{color:#3e434a;font-size:16px;font-weight:400;line-height:25px;margin:0;padding:0 0 21px}
.error{background:#f8f2f1;color:#930;border-bottom:1px solid #e9d5d1;border-top:1px solid #e9d5d1;font-size:16px}
.cta,.error{margin:0 0 34px;padding:20px 39px;text-align:left}
.cta{background:#f1fafe;border-bottom:1px solid #dae3ea;border-top:1px solid #dae3ea;color:#1f1f1f;font-size:14px}
.cta .action{padding:0 0 0 40px;line-height:1.8;min-height:32px}
.cta .icon-warning{background:transparent url(/img/emails/icon_warning.gif) 0 0 no-repeat}
.cta .icon-mistake{background:transparent url(/img/misc/confirmations/icon_mistake.gif) 5px top no-repeat}
.cta .icon-added{background:transparent url(/img/misc/confirmations/icon_added.gif) 5px top no-repeat}
@media (max-width:580px){body{padding:1px}
.container{padding:12px 0}
.instructions,.title{padding:0 10px}
.instructions h2,.title h2{font-size:22px}
.instructions h3,.title h3{font-size:18px}
.cta,.error{padding:10px 7px}}
</style>
</head>
<body>
<div class="container" style="display: <?php echo $display_general; ?>">
    <div class="title">
        <div class="completed">
        <h2 style="display: <?php echo $display; ?>">Merci de nous avoir suivi</h2>
        <p><?php echo $reponse; ?></p>
        </div>
    </div>
</div>
</body>
</html>