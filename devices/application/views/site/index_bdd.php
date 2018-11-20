<?php

$this->load->helper('url');
// include(base_url("devices/application/libraries/getid3/getid3.php"));
require_once(APPPATH.'libraries/getid3/getid3.php');


$array_rubriques = array();
foreach ($rubriques as $arr) {
    foreach ($arr as $value) {
        $array_rubriques[] = $value;
    }
}

$array_rubriquesId = array();
foreach ($rubriquesId as $arr) {

    $array_rubriquesId[$arr['id_rubrique']]=$arr['titre'];
}

$array_users = array();

foreach ($users as $arr) {
    $id_auteur = html_entity_decode( $arr['id_auteur'], ENT_QUOTES, "utf-8" );
    $nom = html_entity_decode( $arr['nom'], ENT_QUOTES, "utf-8" );
    $array_users[$id_auteur]=$nom;
}



$array_spip_users_liens = array();

	foreach ($usersLiens as $arr) {
		if($arr['objet']=="article"){
			$id_auteur = html_entity_decode( $arr['id_auteur'], ENT_QUOTES, "utf-8" );
			$id_article = html_entity_decode( $arr['id_objet'], ENT_QUOTES, "utf-8" );
			//$id_article = html_entity_decode( $data->id_objet, ENT_QUOTES, "utf-8" );
			//$array_spip_auteurs_liens[$id_article]=$id_auteur;
			$array_spip_users_liens[$id_article]=$array_users[$id_auteur];
		}
	}



$array_spip_documents = array();

foreach ($documents as $arr) {

    if($arr['extension']=='mp3'){



foreach ($liens as $arr2) {

    if($arr['id_document']==$arr2['id_document']){
    		$array_spip_documents_liens[$arr2['id_objet']]=$arr['fichier'];
    }
}


    }


}



/* $icon_siteOR = base_url('IMG/siteon0.png');
$order   = array("/devices");
$replace = '';
$icon_site = str_replace($order, $replace, $icon_siteOR); */

/* $title = $title[0]['valeur'];
$accroche = $slogan[0]['valeur']; */
?>



<!-- div push footer -->
<div>



	<div class="container-articles">


	<?php

	$url_play = base_url('images/play.png');

	foreach ($articles as $article) {

		$id_rubrique = $article['id_rubrique'];
		$id_rubrique_class = "rubrique-".$id_rubrique;
		$id_rubrique_class2 = $id_rubrique_class;
		$id_article = $article['id_article'];
		$status_article = $article['statut'];
		$titre = $article['titre'];
		$id_edittopic = "edittopic-".$article['id_article'];
		$date = substr($article['date'], 0, 10);
		$resume = $article['descriptif'];
		$chapeau = $article['chapo'];
		$texteOR = $article['texte'];
		$order   = array("-#");
		$replace = '<br />-';
		$texte = str_replace($order, $replace, $texteOR);
		$user_article = $array_spip_users_liens[$id_article];
		$user_article = ucfirst($user_article);
		$rubrique = $array_rubriquesId[$id_rubrique];
		@$mp3 = $array_spip_documents_liens[$id_article];
		$mp3OR = base_url($mp3);
		$order   = array("devices");
		$replace = 'IMG';
		$mp3 = str_replace($order, $replace, $mp3OR);
		$tmp = explode('/IMG/',$mp3)[1];
		$mp3Path = '../IMG/'.$tmp;
		$duree = "";
		try {
			$getID3 = new getID3;
			$file = @$getID3->analyze($mp3Path);
			$playtime_seconds = @$file['playtime_seconds'];
			$duree = gmdate("H:i:s", $playtime_seconds);
		}
		catch(Exception $e){
			//echo $duree;
		}
		$display_duree = "display:none;";
		$display_tag = "display:none;";
		$display_mp3 = "display:none;";
		$display_article = "display:none;";
		$display_chapeau = "display:none;";
		$display_texte = "display:none;";
		$url_icon = "";
		$extensions = ['.jpg','.png','.gif'];
		foreach ($extensions as $ext) {
			$url_iconOR = base_url('IMG/arton'.$id_article.$ext);
			$order   = array("devices/");
			$replace = '';
			$url_iconTest = str_replace($order, $replace, $url_iconOR);
			$file_headers = @get_headers($url_iconTest);
			if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
				$exists = false;
			}
			else {
				$exists = true;
				$url_icon = str_replace($order, $replace, $url_iconOR);
			}
			if($exists==true){break;}
		}

		if($status_article =="prepa"){
			$id_rubrique_class2 = ".rubrique-soon";
			$rubrique = "à venir";
			$display_article = "display:none;";
		}
		if($status_article =="publie"){
			$display_duree = "display:inline-block;";	
			$display_mp3 = "display:block;";
			$display_article = "display:block;";
		}
		if ($status_article == "publie" AND strlen($texte)>0 OR strlen($chapeau)>0){
			$display_article = "display:block;";
		}
		if (strlen($chapeau)>0){
			$display_chapeau = "display:block;";
		}
		if (strlen($texte)>0){
			$display_texte = "display:block;";
		}
		if (strlen($texte)==0 OR strlen($chapeau)==0){
			$display_article = "display:none;";
		}

		$data = array(
			'id_article' => $id_article, 
			'user_article' => $user_article, 
			'article' => $article,
			'titre' => $titre,
			'resume' => $resume,
			'chapeau' => $chapeau,
			'status_article' => $status_article,
			'texte' => $texte,		
			'id_edittopic' => $id_edittopic,
			'rubrique' => $rubrique, 
			'id_rubrique_class' => $id_rubrique_class,
			'id_rubrique_class2' => $id_rubrique_class2,
			'date' => $date, 		
			'mp3' => $mp3, 
			'duree' => $duree,
			'display_duree' => $display_duree,
			'display_tag' => $display_tag,
			'display_mp3' => $display_mp3,
			'display_article' => $display_article,
			'display_chapeau' => $display_chapeau,
			'display_texte' => $display_texte,
			'url_play' => $url_play ,
			'url_icon' => $url_icon
		);

		$this ->load ->view('site/article_bdd_resume', $data);

	}

?>






