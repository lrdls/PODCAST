
<?php
// Dans les versions de PHP antiéreures à 4.1.0, la variable $HTTP_POST_FILES
// doit être utilisée à la place de la variable $_FILES.

//$uploaddir = dirname(__FILE__).'/uploads/';
$uploaddir = '../tmp/upload/';
/* $array_ext = ['mp3','mp4']; */
$array_ext = ['mp3'];
$chain = implode(", ", $array_ext);
// $uploaddir = '';
//echo $uploaddir;
echo '<pre>';
$uploadfile = $uploaddir . basename($_FILES['fileToUpload']['name']);
//echo $uploadfile;

$ext = explode('.',$uploadfile);
$ext = array_values(array_slice($ext, -1))[0];



echo '<pre>';
if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadfile)) {

/*     $mimetype = mime_content_type($_FILES['fileToUpload']['tmp_name']);
    echo $mimetype; */

/*     if(in_array($mimetype, array('image/jpeg', 'image/gif', 'image/png'))) { */
    if(in_array(strtolower($ext), $array_ext)){
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploaddir . $_FILES['fileToUpload']['name']);
        echo "Le fichier a été <br>téléchargé avec succès.\n";
        echo "<br>";
        echo "Il ne vous reste plus <br>qu'à le lier ... ";
        echo "<br>";
        echo " <img src='doc_upload2.jpg'> ";
        echo "<br>";
        echo "Reload page (refresh) !";
        echo "<br><br>";
/*         echo "<b>Vous pouvez fermez cette fenêtre!</b>";   */   

     } else {
         echo 'Votre fichier doit être <br>un '.$chain.' !';
     }
    
} else {
    echo "Attaque potentielle par téléchargement de fichiers.
          Voici plus d'informations :\n";
          echo "Error\n";
}

/* echo 'Voici quelques informations de débogage :';
print_r($_FILES);
 */
echo '</pre>';

?>
