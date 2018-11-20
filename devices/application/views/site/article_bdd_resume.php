<div>
  <div class="well article <?= $rubrique ?>" id="<?= $id_article ?>" >
      	<div class="media">
	      	<a class="pull-left" href="#"><img class="media-object img-icon " src="<?= $url_icon ?>"alt=""></a>
	  		<div class="media-body" >
				<table border="0" width="100%" style="width:100%;margin-top: 0;height:var(--size-height-imgicon);">
				<tbody>
				<tr>
				<td style=""  valign="top">
					<h4 class="media-heading article-titre">
						<span style="width:100%;padding:0;margin-top: 0;"><?= $titre ?></span>
					</h4>
					<p><span class="article-resume"><b>Résumé : <?= $resume ?></b></span></p>
				</td>
				<td align="right" valign="top">
					<span class="article-user"><?= $user_article ?>	</span>
					<?php if ($status_article == "publie"): ?>
					<p>
					<div style="display:inline-block;">
						<span class="article-date"><?= $date ?></span>
					</div>
					</p>
					<?php else: ?>
					<?php endif; ?>
				</td>
				</tr>
				</tbody>
				</table>
				<p class="text-left">
					<span class="label label-default article-tag <?= $id_rubrique_class ?>"><?= $rubrique ?></span>
					<span class='badge article-duree' style="<?= $display_duree; ?>"><?= $duree?></span>
					<span class="label label-default article-tag <?= $id_rubrique_class2 ?>" style="<?= $display_tag; ?>"><?= $rubrique ?></span>
				</p>
				<p class="text-left">
						<span class="span-controls" style="<?= $display_mp3; ?>">
							<br>
							<audio class="audio" controls="controls">
								<source type="audio/mpeg" src="<?= $mp3 ?>">
							</audio>
						</span>	
					<br>
				</p>	
				<p>
				<div style="<?= $display_article; ?>">
						<div  class="edittopic" id="<?= $id_edittopic ?>">
							<span class="article-plus">[ plus d'infos ]</span>
						</div>
						<div class="article-texte t-edit-cont">
								<br><span class="article-chapeau"><?= $chapeau ?></span><br>
								<br><span class="article-text"><?= $texte ?></span>
						</div>
					</div>
				</p>
	       </div>
    	</div>
	</div>
</div>