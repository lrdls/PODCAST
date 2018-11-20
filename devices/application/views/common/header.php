<!DOCTYPE  html>
<?php
  header('Access-Control-Allow-Origin: *');
  $this->load->helper('url');
  $this->load->model('eps_model');
  $icon_siteOR = base_url('IMG/siteon0.png');
  $order   = array("/devices");
  $replace = '';
  $icon_site = str_replace($order, $replace, $icon_siteOR);
  $accroche = $slogan[0]['valeur'];
  $title = $title[0]['valeur'];

  $keywords_rubriques = [];

  foreach ($rubriques as $rubrique) {
    if($rubrique['statut']=='publie'){
      $keywords_rubriques[] = $rubrique['titre'];
    }
  }
  $keywords_articles = [];
  foreach ($articles as $article) {
    if($article['statut']=='publie'){
      $keywords_articles[] = $article['titre'];
    }
  }

$list_keyword_rubriques = implode(", ",$keywords_rubriques);
$list_keyword_articles = implode(", ",$keywords_articles);

?>
<html>
<head>

<title><?=  $title ?></title>

<meta charset="UTF -8">
<meta http-equiv="X-UA -Compatible" content="IE=edge">
<meta name="viewport" content="width=device -width , initial -scale =1">
<meta name="Content-Type" content="UTF-8">
<meta name="Content-Language" content="fr">
<meta name="Copyright" content="LRDS">
<meta name="Author" content="Vincseize, Charles POTTIER, J. Néret">
<meta name="Publisher" content="LRDS">
<meta name="Revisit-After" content="15 days">
<meta name="Robots" content="all">
<meta name="Rating" content="general">
<meta name="Distribution" content="global">
<meta name="Category" content="histoire">
<meta name="description" content="le podcast dans lequel on se raconte des histoires">

<meta name="keywords" content="podcast, et puis soudain, etpuissoudain, anecdote, anecdotes, histoire, histoires">

<meta name="keywords" content="<?php echo $list_keyword_rubriques;?>">
<meta name="keywords" content="<?php echo $list_keyword_articles;?>">

<meta name="generator" content="Spip">
<meta name="generator" content="Codeigniter">
<meta name="generator" content="Php">

<link rel="stylesheet" href="<?=base_url('assets/css/bootstrap3.3.2/bootstrap.min.css');?>">
<link rel="stylesheet" href="<?=base_url('assets/css/navbar.css');?>"> 
<link rel="stylesheet" href="<?=base_url('assets/css/mediaelementplayer.min.css');?>"> 
<link rel="stylesheet" href="<?=base_url('assets/css/player.css');?>"> 
<link rel="stylesheet" href="<?=base_url('assets/css/jqueryui/1.11.2/themes/smoothness/jquery-ui.min.css');?>"> 
<link rel="stylesheet" href="<?=base_url('assets/css/font-awesome.min.css');?>">
<link  rel="stylesheet" href="<?=base_url('assets/css/bootstrap.4.0.0-beta.min.css');?>">

<link  rel="stylesheet" href="<?=base_url('assets/css/header.min.css');?>">
<!-- <link  rel="stylesheet" type="text/css" href="<?=base_url('assets/css/header.css');?>"> -->

<!-- 
HTML5  shim  and  Respond.js
for IE8  support  of  HTML5  elements  
and media queries  
-->
<!-- WARNING: Respond.js doesn ’t work if you  view  the  page  via  file ://  -->
<!--[if lt IE 9]>
<script  src="https ://oss.maxcdn.com/html5shiv /3.7.2/ html5shiv.min.js"
></script>
<script  src="https ://oss.maxcdn.com/respond /1.4.2/ respond.min.js"></script>
<![ endif]-->

</head>

<body>
  <div class="bandeau">

    <form id="formX" class="search-form">
      <input id="filter" type="search" class="sb" name="q" autocomplete="off" placeholder="search" />
      <button id="sbtn" type="submit" class="sbtn icon-search-clear"><font face="arial"><b>X</b></font></button>
    </form>  

    <table width="100%" border="0">
    <tbody>
    <tr>
    <td class="td--icon-site">
      <img class="icon-site" src='<?= $icon_site ?>' alt='icone'>
    </td>
    <td>
      <table border="0">
      <tbody>
      <tr>
      <td width="25px"></td>
      <td valign="top"><span class="site-title"><?= $title ?></span></td>
      </tr>
      <tr>
      <td></td>
      <td valign="top"><span class="site-accroche"><?= $accroche ?></span></td>
      </tr>
      </tbody>
      </table>
    </td>
    </tr>
    </tbody>
    </table>

  </div>

<!--Navbar -->
<nav class="navbar navbar-expand-md navbar-dark navbarMedia">
    <a class="navbar-brand navbar-brandMedia" href="#" onclick="location.reload();">HOME</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarToggle">
        <ul class="navbar-nav mr-auto navbar-navMedia">
          <?php
          foreach($rubriques as $rubrique){
            if(strtoupper($rubrique['titre'])!='ADMINISTRATIONINTERNE'){
                $id=$rubrique['titre'];
                $rubrique=$rubrique['titre'];
                echo "<li class='nav-item'>";
                  echo "<a class='nav-link waves-effect waves-light navbar-click' href='#' id='$id'>";
                    echo $rubrique;
                  echo "</a>";
                echo "</li>";
            }
          }
          ?>
        </ul>
        <form class="form-searchinline">
            <button type="submit" class="icon-search"><i class="fa fa-search btSearch"></i></button>
        </form>
    </div>
</nav>
<!--/.Navbar -->