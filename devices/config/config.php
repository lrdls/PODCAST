<?php

return array(

    // Define your path
    'ip' => '127.0.0.1', // IP not domain name !!!

    // profil
    'name_site' => 'Et puis soudain', // Nom de votre site podcast
    'titleNewsletter' => 'Podcast(s) du mois', // intitulé du mail

    // mail
    'expediteur' => 'le.reve.de.la.salamandre@gmail.com',
    //array('mail1@gmail.com','mail2@gmail.com','etc@gmail.com','neret.jeremy@gmail.com'),
    'mailsAuthors_to_exclude' => array('akinamuri@gmail.com'),
    
    // delay , secu pour eviter server flood et bannissement, mini 1 sec
    'delay_sendmail' => '1',

    // second copyright optionnel, comment the line
    'copyright2' => 'lrds',

    // desabonnement
    'text_desabo_ok' => 'Votre désabonement à la newsletter du podcast ET PUIS SOUDAIN,<br/> est à présent effectif.',

    // extension fichiers type media seulement!!! à exclure lors de la sauvegarde auto
    'extToExclude' => array('mp3','mp4','ogg','ogv','mov','zip'),

)

?>