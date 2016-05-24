<?php
require_once('classes/ListGenerator.class.php') ;

function get_FormProfile($title, $user, $consultant, $msg) {
	global $language, $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;

	$combobox=""; $msgbox = "" ;
	$title = T6 . "<h1>" . $title . "</h1>\n" ;

	$log = $log . "Je suis dans données personnelles<br>" ;
	$pseudo = "<input type='text' name='pseudo' value='" . $user->get_Pseudo() . "'>" ;
	$hidden_id =  "<input type='hidden' name='id' value='" . $user->get_IdMember() . "'>" ;
	$hidden_consultant =  "<input type='hidden' name='consultant' value='" . $consultant . "'>" ;
	if($consultant == AUTOCONSULTANT){
		$log = $log . "Je suis dans Profile en tant qu'autoconsultant.<br>" ;
		$email = "<div name='email' class='disabled_field'>" . $user->get_Email() . "</div>" ;
		$hidden_email = "<input type='hidden' name='email' value='" . $user->get_Email() . "'>" ;
		$privilege = "<div name='privilege' class='disabled_field'>" . get_Privileges($user->get_Privilege()) . "</div>" ;
		$hidden_privilege = T9 . "<input type='hidden' name='id_privilege' value='" . $user->get_Privilege() . "'>\n" ;
		$current_pass = "<input type='password' name='current_password' id='current_pass' placeholder='**********'>" ;
		$new_pass = "<input type='password' name='new_password' id='new_password' placeholder='**********' oninput='checkPasswords()'>" ;
		$confirm_pass = "<input type='password' name='confirm_password' id='confirm_password' placeholder='**********' oninput='checkPasswords()'>" ;
		$hidden = $hidden_id . $hidden_email . $hidden_privilege . $hidden_consultant;
	}elseif($consultant == SUPERCONSULTANT){
		// id_member est une façon un peu artificielle d'indiquer qu'on est en superconsultant.
		$hidden_member =  "<input type='hidden' name='id_member' value='" . $user->get_IdMember() . "'>" ;
		$log = $log . "Je suis dans Profile en tant que superconsultant.<br>" ;
		$email = "<input type='email' name='email' value='". $user->get_Email() . "'>" ;
		$privilege = SelectOptionMaker(get_Privileges(ALL_PRIVILEGES), "id_privilege", $user->get_Privilege()) ;
		$current_pass = "<div name='current_pass' id='current_password' class='disabled_field'>**********</div>" ;
		$new_pass = "<div name='new_password' id='new_password' class='disabled_field'>**********</div>" ;
		$confirm_pass = "<div name='confirm_password' id='confirm_password' class='disabled_field'>**********</div>" ;
		$hidden = $hidden_id . $hidden_member . $hidden_consultant ;
	}
	$languages = get_LanguagesSelectOption($user->get_Language()) ;
	$devise = "<textarea name='devise'>" . $user->get_Devise() . "</textarea>" ;

	$pseudo = LabelLi('pseudo', 'Pseudo', $pseudo, T8) ; 
	$email = LabelLi('email', 'Email', $email, T8) ;
	$current_pass = LabelLi('current_password', 'MdP courant', $current_pass, T8) ; 
	$new_pass = LabelLi('new_password', 'MdP nouveau', $new_pass, T8) ; 
	$confirm_pass = LabelLi('confirm_password', 'Confirmer MdP', $confirm_pass, T8) ; 
	$privilege = LabelLi('disabled_field', 'Privilege', $privilege, T8) ;
	$languages = LabelLi('language', 'Language', $languages, T8) ;
	$devise = LabelLi('devise', 'Devise', $devise, T8) ;

	$border = true ;

	$name = "personal_profile" ;
	$url = "index.php?mode=profile&profile=recpersonalprofile" ;
	$legend = $language[PERSONAL_DATA] ;
	$form_personal = $pseudo . $email . $privilege . $hidden ;
	$form_personal = FormFieldsetMaker($form_personal, $name, $url, $legend, $border) ;

	$name = "secret_profile" ;
	$url = "index.php?mode=profile&profile=recsecretprofile" ;
	$legend = $language[SECRET_DATA] ;
	$form_secret = $current_pass . $new_pass . $confirm_pass . $hidden ;
	$form_secret = FormFieldsetMaker($form_secret, $name, $url, $legend, $border) ;

	$name = "social_profile" ;
	$url = "index.php?mode=profile&profile=recsocialprofile" ;
	$legend = $language[SOCIAL_DATA] ;
	$form_social = $languages . $devise . $hidden  ;
	$form_social = FormFieldsetMaker($form_social, $name, $url, $legend, $border) ;

	if($msg!=""){$msgbox = "<div class='msgbox'>" .  $msg . "</div>" ;}
	$balise_div = 
		T6 . "<div class='formbox formbox_etroit'>\n" . 
			$form_personal . $form_secret . $form_social  . $msgbox . 
		T6 . "</div><!-- formbox -->\n" ;

	$contenu = $title . $balise_div ;
	return $contenu ;
}

function get_ArticleForm($id_article, $scan){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	global $language ;

	$title = T6 . "<h1>" . $language[TITLE_EDIT_ARTICLE] . $scan[$id_article]['name'] . "</h1>\n" ;

	$options ="" ;
	foreach($scan as $id => $record){
		if($id == $id_article){
			$attribute=" selected" ;
		}else{
			$attribute="" ;
		}
		$margin = "style='padding-left:" . $record['level'] * 15 . "px;'" ;
		$js = "ondblclick=window.location='index.php?mode=article&article=form_content&id_article=" . $id . "';" ;
		$options = $options . "<option " . $margin . " value='" . $id . "'" . $attribute . " " . $js . ">" . $record['name'] . "</option>\n" ;
	}
	$select_family = "<select size='6'>\n" . $options . "</select>\n" ;

	$formulaire =
		"<form action='index.php?mode=article&article=form_content' name='MesArticles' method='post' onsubmit='return test_ChampsMdp()'>" .
		"<fieldset>" .
			"<ul class='formlist'>" . 
				"<li>" . $select_family . "</li>" .
			"</ul>" .
			"<div class='porte_SubmitButton'><input type='submit' class='OKButton'></div>" .
		"</fieldset></form>" ;

	$balise_div = "<div class='formbox formbox_large'>" . $formulaire . "</div>" ;
	$retour = $title . $balise_div ;

	return $retour ;

}


function get_EditArticleContent($article, $msg=""){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	global $language ;
	$msgbox="";
	if($article['id']==0){$title=
		T6 . "<h1>" . $language[TITLE_EDIT_ARTICLE] . " nouvel article</h1>\n" ;
	}else{
		$title = T6 . "<h1>" . $language[TITLE_EDIT_ARTICLE] . $article['title'] . "</h1>\n" ;
	}
	/*
	$combobox = "" ;
	$hidden_id_article = "<input type='hidden' name='id_article' value='" . $article['id'] . "'>" ;
	$hidden_id_parent = "<input type='hidden' name='id_parent' value='" . $article['id_parent'] . "'>" ;
	$titre_article = "<input type='text' name='article_title' value='" . $article['title'] . "'>" ;
	$nom_article = "<input type='text' name='article_name' value='" . $article['name'] . "'>" ;

	$tab_status = get_Status(ALL) ;
	foreach($tab_status as $id => $status){
		if($id == $article['id_status']){
			$attribute=" selected" ;
		}else{
			$attribute="" ;
		}
		$combobox = $combobox . 
			"<option value='" . $id . "'" . $attribute . ">" . $status['label'] . "</option>\n" ;
	}
	$str_statut = "<select name='id_status'>" . $combobox . "</select>" ;
	$texte_article = "<textarea name='article_text'  class='editor'>" . $article['text'] . "</textarea>" ;
	//$texte_article = "<textarea class='editeur'>" . $article['text'] . "</textarea>" ;


	$titre_article = LabelLi('titre_article', 'Titre de l article', $titre_article, T7) ; 
	$nom_article = LabelLi('nom_article','Nom de l article', $nom_article, T7) ; 
	$str_statut = LabelLi('statut_article', 'Statut', $str_statut, T7) ;
	$texte_article = LabelLi('texte_article', 'Texte de l article', $texte_article, T7) ;
	//$select_family = LabelLi('select_family', 'Parent', $select_family, T7) ;



	$liste_champs = $titre_article . $nom_article . $str_statut . $texte_article . $hidden_id_article . $hidden_id_parent ; // . $select_family;

	$formulaire =
		"<form action='index.php?mode=article&article=rec_content' name='MesArticles' method='post' onsubmit='return test_ChampsMdp()'><fieldset>" .
			"<ul class='formlist'>" . 
				$liste_champs . 
			"</ul>" .
			"<div class='porte_SubmitButton'><input type='submit' class='OKButton'></div>" .
		"</fieldset></form>" ;
	if($msg!=""){$msgbox = "<div class='msgbox'>" .  $msg . "</div>" ;}
	*/
	require_once('classes/editor.class.php');

	$editeur = new Editeur($article) ;
	$formulaire = $editeur->get_Interface();


	$balise_div = "<div class='formbox formbox_large'>" . $formulaire . $msgbox . "</div>" ;
	$retour = $title . $balise_div ;

	return $retour ;
}

function get_EditArticleParent($id_article, $scan, $family){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	global $language ;

	$id_parent = $scan[$id_article]['id_parent'] ;
	switch($family){

		case 1:	//parent
		$title = T6 . "<h1>Sélection du parent de " . $scan[$id_article]['name'] .  "</h1>\n" ;
		$explanations = 
		"<li>En définissant un parent, vous placez l'article courant en tête de fratrie, enfant de ce parent.</li>" ;
		$article_list = $scan ;
		$id_selected = $id_parent ;
		$submode = "rec_parent" ;
		break;

		case 2:	// brother
		$title = T6 . "<h1>Sélection du petit frère de " . $scan[$id_article]['name'] .  "</h1>\n" ;
		$explanations = 
			"<li>En définissant un petit frère, vous placez l'article courant comme petit frère de ce grand frère. 
			Pour placer l'article courant en queue de fratrie, sélectionnez son parent.</li>" ;
		$brotherhood=array() ;
		foreach($scan as $id => $article){
			if($article['id_parent']==$id_parent && $id != $id_article){
				$brotherhood[$id]=$article ;
			}
		}
		$article_list = $brotherhood ;
		$id_selected = $scan[$id_article]['id_previous'] ;
		$submode = "rec_brother" ;
		break;
	}


	$options ="" ;
	foreach($article_list as $id => $record){
		if($id == $id_selected){
			$attribute=" selected" ;
		}else{
			$attribute="" ;
		}
		if($family==1){$margin = "style='padding-left:" . $record['level'] * 15 . "px;' " ;}else{$margin="";}
		$value= "value='" . $id . "' " ;
		$tag_id = "id='" . $scan[$id_article]['id'] . "' " ;
		$options = $options . 
			"<option " . $margin . $value . $tag_id . $attribute . ">" . 
				$record['name'] . 
			"</option>" ;
	}
	$select = "<li><select name='id_from_scandisplay' size='6'>" . $options . "</select></li>" ;
	$hidden = "<input type='hidden' name='id_article' value='" . $id_article . "'>" ;
	$formulaire =
		"<form action='index.php?mode=article&article=" . $submode . "' name='MesArticles' method='post' onsubmit='return test_ChampsMdp()'><fieldset>" .
			"<ul class='formlist'>" . 
				$select . $explanations . $hidden .
			"</ul>" .
			"<div class='porte_SubmitButton'><input type='submit' class='OKButton'></div>" .
		"</fieldset></form>" ;

	$balise_div = "<div class='formbox formbox_large'>" . $formulaire . "</div>" ;
	$retour = $title . $balise_div ;
	return $retour ;
}

function get_InternalLinks($scan, $links){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	$options = "" ; $link_list ="" ;

	foreach($links as $id=>$link){
		$link_list=$link_list . "<p><p style='display:inline;'>" .  $link . " </p><a href='index.php?mode=article&article=editliensinternes&delete=" . $id . "' style='float:right;'>Supprimer</a></p>\n" ;
	}

	foreach($scan as $id => $record){
		/*
		if($id == $id_article){
			$attribute=" selected" ;
		}else{*/
			$attribute="" ;
		/*}
		*/
		$margin = "style='padding-left:" . $record['level'] * 15 . "px;'" ;
		//$js = "ondblclick=window.location='index.php?mode=article&article=form_content&id_article=" . $id . "';" ;
		$options = $options . T11 . "<option " . $margin . " value='" . $id . "'" . $attribute . ">" . $record['name'] . "</option>\n" ;
	}
	//$select_family = "<select size='6'>\n" . $options . "</select>\n" ;
	$select = T10 . "<select name='int_links' size='10'>\n" . $options . T10 . "</select>\n" ;
	$title = T6 . "<h1>Liens internes de l'article</h1>\n" ;
	$formulaire =
		T7 . "<form action='index.php?mode=article&article=editliensinternes' name='MesArticles' method='post' onsubmit='return test_ChampsMdp()'><fieldset>\n" .
			T8 . "<ul class='formlist'>\n" . 
				T9 . "<li style='display:inline-block;'>\n" . 
					$select . 
					//T10 . "<input type=submit value='delete'>\n" .
				T9 . "</li>\n" .
				T9 . "<li style='display:inline-block;vertical-align:top;width:40%;'>\n" . 
					$link_list .
				T9 . "</li>\n" . 
			T8 . "</ul>" .
			"<div class='porte_SubmitButton'><input type='submit' name='record' class='OKButton'></div>\n" .
		T7 . "</fieldset></form>" ;

	$balise_div = T6 . "<div class='formbox formbox_large'>\n" . $formulaire  . T6 . "</div>\n" ;
	$retour = $title . $balise_div ;
	return $retour ;

}

/*
function get_ExternalLinks($links){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	$options = "" ; 
	foreach($links['optionlinks'] as $id=>$link){
		$options=$options . T10 . "<option value='" . $id . "'>$id." . $link['name'] . "</option>\n" ;
	}
	$select = T9 . "<select name='ext_links' id='ext_links' size='10'>\n" . $options . T9 . "</select>\n" ;
	$title = T5 . "<h1>Liens externes de l'article</h1>\n" ;	
	$formulaire =
		T5 . "<form action='index.php?mode=article&article=editliensexternes' name='MesArticles' method='post' onsubmit='return test_ChampsMdp()'><fieldset>\n" .
			T6 . "<ul class='formlist'>\n" . 
				T7 . "<li>\n" . 
					T8 ."<div style='max-width:50%; display:inline'>\n" . $select . T8 . "</div>\n" . 
					T8 ."<div style='max-width:50%; display:inline'>\n" . 
						T9 ."<input type=button value='new' style='vertical-align:top;' onclick='NewButton()'>\n" .
						T9 ."<input type=button value='edit' style='vertical-align:top;' onclick='EditButton()'>\n" .
						T9 ."<input type=submit name='delete' value='delete' style='vertical-align:top;' onclick='DeleteButton()'>\n". 
					T8 ."</div>\n" .
				T7 ."</li>\n" .
				T7 ."<li><label for='ext_link_name'>Label</label><input type='text' name='ext_link_name' id='ext_link_name' style='display:block;width:350px'></li>\n" .
				T7 ."<li><label for='ext_link_url'>url</label><input type='text' name='ext_link_url' id='ext_link_url' style='display:block;width:350px'></li>\n" .
			T6 . "</ul>\n" .
			T6 . "<input type='hidden' name='id_ext_link' id='id_ext_link' value=''>\n" .
			T6 . "<div class='porte_SubmitButton'><input type='submit' name='record' class='OKButton'></div>\n" .
		T5 . "</fieldset></form>\n" ;
	$balise_div = T5 . "<div class='formbox formbox_large'>\n" . $formulaire . "<a href='functions/test_js.html'>Tests Javascript</a>\n" . T5 . "</div><!-- formbox bormbox_large-->\n" ;
	$retour = $title . $balise_div . JSButtonAction($links['optionlinks'] ) ;
	return $retour ;
}
*/

function get_ExternalLinks($links){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	$options = "" ; $first_loop=true ; 
//var_dump($links) ;
	foreach($links['optionlinks'] as $id=>$link){
		$data = "data-id='" . $id . "' data-name='" . $link['name'] . "' data-url='" . $link['url'] . "' " ;
		//if($id!='0'){ // c'est peut-être important. Ne pas supprimer avant d'avoir analysé cette fonction.
			if($first_loop){
				$sel="selected" ;
				$first_loop=false ;
			}else{
				$sel="" ;
			}
		$options=$options . T10 . "<option value='" . $id . "' " . $sel . " " . $data . ">" .  $id . ". " . $link['name'] . "</option>\n" ;
		//}
	}
	$select = T9 . "<select name='ext_links' id='ext_links' size='10'>\n" . $options . T9 . "</select>\n" ;
	$title = T5 . "<h1>Liens externes de l'article</h1>\n" ;
	$formulaire =
		T5 . "<form action='index.php?mode=article&article=editliensexternes' name='MesArticles' method='post' onsubmit='return test_ChampsMdp()'><fieldset>\n" .
			T6 . "<ul class='formlist'>\n" . 
				T7 . "<li>\n" . 
					T8 ."<div style='max-width:50%; display:inline'>\n" . $select . T8 . "</div>\n" . 
					T8 ."<div style='max-width:50%; display:inline'>\n" . 
						//T9 ."<input type=button value='new' style='vertical-align:top;' onclick='NewButton()'>\n" .
						//T9 ."<input type=button value='edit' style='vertical-align:top;' onclick='EditButton()'>\n" .
						T9 ."<input type=submit name='delete' value='delete' style='vertical-align:top;' onclick='DeleteButton()'>\n". 
					T8 ."</div>\n" .
				T7 ."</li>\n" .
				T7 ."<li><label for='ext_link_name'>Label</label><input type='text' name='ext_link_name' id='ext_link_name' style='display:block;width:350px'></li>\n" .
				T7 ."<li><label for='ext_link_url'>url</label><input type='text' name='ext_link_url' id='ext_link_url' style='display:block;width:350px'></li>\n" .
			T6 . "</ul>\n" .
			T6 . "<input type='hidden' name='id_ext_link' id='id_ext_link' value=''>\n" .
			T6 . "<div class='porte_SubmitButton'><input type='submit' name='record' class='OKButton'></div>\n" .
		T5 . "</fieldset></form>\n" ;

	$balise_div = T5 . "<div class='formbox formbox_large'>\n" . $formulaire . "<a href='functions/test_js.html'>Tests Javascript</a>\n" . T5 . "</div><!-- formbox bormbox_large-->\n" ;
	$retour = $title . $balise_div . JSButtonAction($links['optionlinks'] ) ;
	return $retour ;

}

function get_Images($content, $dir){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	global $language ;

	$title = T6 . "<h1>Images</h1>\n" ;
	/*
	$fieldset = $content ;
	$formulaire =
		"<form action='index.php?mode=images&directory=$dir' name='image_management' method='post'><fieldset>" .
			"<ul class='formlist'>" . 
				$fieldset . 
			"</ul>" .
			"<div class='porte_SubmitButton'><input type='submit' class='OKButton'></div>" .
		"</fieldset></form>" ;
*/
	$formulaire =
			"<div class='images'>\n" .
				$content .
			"</div>\n" .
			"<div class='dashboard'>" .
				"&nbsp;" .
				"<form action='" . "index.php?mode=images&directory=$dir' method='post'><fieldset>" .
					"<label for='new_dir'>New Directory</label>" .
					"<input type='text' name='new_dir'>" .
					"<input type='submit' name='subNewDir'>" .
					"<br>" .
					"<label for='remove_dir'>Remove actual directory (must be empty)</label>" .
					"<input type='submit' name='remove_dir' value='remove'> " .
					"<br>" .
					"<input type=text id='selected_file' name='selected_file' placeholder='Click on an image'>" .
					"<input type='submit' id='remove_file' name='remove_file' value='Remove file'>" .
					"<br>" .
					"<input type=text id='new_name' name='new_name' placeholder='new image name'>" .
					"<input type='submit' id='rename_file' name='rename_file' value='Rename file'>" .
				"</fieldset></form>" .
				"<form action='" . "index.php?mode=images&directory=$dir' method='post' enctype='multipart/form-data'>" .
				"<fieldset>" .
					"<input type='hidden' name='MAX_FILE_SIZE' value='2097152'>" .
					"<input type='file' name='nom_du_fichier'>" .
					"<br>" .
					"<input type='submit' value='Envoyer'>" .
				"</fieldset></form>" .
			"</div>" ;






	$balise_div = "<div class='formbox formbox_large'>" . $formulaire . "</div>" ;
	$retour = $title . $balise_div ;
	return $retour ;
}

function get_Administration($preferences){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	global $language ;
//var_dump($preferences) ;
	$select_lang = get_LanguagesSelectOption($preferences['default_lang']) ;
	//$select_lang = LabelLi('language', 'Language', $select_lang, T8) ;



	$title = T6 . "<h1>" . $language[TITLE_ADMINISTRATION] . "</h1>\n" ;
	$options = "" ;
	foreach($registermodes=get_RegisterModes(0) as $id => $rm){
		if($id == $preferences['id_inscription']){$sel=" selected";}else{$sel="";}
		$options = $options . "<option value='" . $id . "'" . $sel . ">" . $registermodes[$id] . "</option>";
	}
	$select = "<select name='id_inscription'>" . $options . "</select>" ; 
	if($preferences['echo_log']=='1'){$checked='checked';}else{$checked="";}
	$fieldset =
			"<li><label for='name'>Nom du site</label>" .
			"<input type='text' name='name' value='" . $preferences['name'] . "'></li>" .
			"<li><label for='rows_per_page'>Nombre de résultats par page</label>" .
			"<input type='number' name='rows_per_page' value='" . $preferences['rows_per_page']  . "' min='1' max='64'></li>" .
			"<li><label for='devise'>Devise du site</label>" .
			"<textarea name='devise'>" . $preferences['devise'] . "</textarea></li>" .
			"<li><label for='echo_log'>Faut-il afficher les messages log?</label>" .
				"<input type='checkbox' name='echo_log' $checked></li>" .
			"<li><label for='id_inscription'>Mode d'inscription (inactif)</label>" .
			$select . "</li>" .
			$select_lang .
			/*
			"<Select name='id_inscription'>" .
				"<option value='1' selected>Inscription directe</option>" .
				"<option value='2'>Inscription à l'email de contrôle</option>" .
				"<option value='3'>Inscription manuelle</option>" .
			"</select></li>" .
			*/
			"<input type=hidden name='id' value='" . $preferences['id'] . "'>" ;				

	$formulaire =
		"<form action='index.php?mode=admin&admin=rec_main_form' name='siteoptions' method='post' onsubmit='return test_ChampsMdp()'><fieldset>" .
			"<ul class='formlist'>" . 
				$fieldset . 
			"</ul>" .
			"<div class='porte_SubmitButton'><input type='submit' class='OKButton'></div>" .
		"</fieldset></form>" ;

	$balise_div = "<div class='formbox formbox_etroit'>" . $formulaire . "</div>" ;
	$retour = $title . $balise_div ;
	return $retour ;
}

function get_CollectionMembers($collection, $pagination){
	global $log ; $log = $log . "<b>" .   __METHOD__  . "</b><br>" ;
	global $language ;

	$title = T6 . "<h1>" . $language[TITLE_MEMBERSLIST] . "</h1>\n" ;
	foreach($collection['members'] as $key => $member){

			$pseudo = $member['pseudo'] ;
			$privilege = get_Privileges($member['id_privilege']) ;
			$lignes[] = 
				"<ul class='element_liste'>" . 
					"<li><a href='index.php?mode=profile&profile=userprofile&id_member=" . $key . "'>" . $pseudo . "</a></li>" . 
					"<li>" . $privilege . "</li>" .
				"</ul>" ;
	}

	$setofitems=array(
		'list'=>$lignes,
		'pagination'=>$pagination) ;
	$setofitems['pagination']['url']="index.php?mode=memberlist" ;
	$l = new ListGenerator ;
	$liste = $l->get_list($setofitems) ;
	$balise_div = "<div>" . $liste . "</div>" ;
	$retour = $title . $balise_div ;
	return $retour ;
}

/* ****************** FONCTIONS AUXILIAIRES ****************** */

// Destinée aux champs, LabelLi ajoute un label et une paire de balises li pour 
// lesquelles il faut prévoir une paire de balises ul.
function LabelLi($for, $label, $champ, $tabulation){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b> (Label for " . $for . " field)<br> " ;

	$LabelLi = $tabulation . 
			"<li>" . 
				"<label for='" . $for . "'>" . $label . "</label>\n" . 
				$tabulation . T1 . $champ . 
			"</li>\n" ;
	return $LabelLi ;
}

function get_LanguagesSelectOption($language_prefix){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	$combobox = "" ;

	$languages['en'] = "English" ;
	$languages['fr'] = "Français" ;

	foreach($languages as $key => $language){
		if($key==$language_prefix){$sel="selected";} else {$sel="";}
		$combobox = $combobox . "<option value='" . $key . "' " . $sel . ">" . $language . "</option>\n" ;
	}
	$combobox = "<select name='language'>" . $combobox . "</select>" ;
	return $combobox ;
}


function SelectOptionMaker($array, $select_name, $selected_item){
	global $log  ; 
	$log = $log . "<b>" . __METHOD__  . "</b> (Combobox " . $select_name . ")<br> " ;
 
	$options = "" ;
	$sel = "" ;
	foreach($array as $key => $item){
		if($selected_item==$key){
			$sel="selected" ;
		}else{
			$sel="" ;
		}
		$options = $options .
			"<option value='" . $key . "' " . $sel . ">" . 
				$item . "</option>\n" ;
	}
	$select_option = "<select name='" . $select_name . "'>\n" . $options . "</select>\n" ;
	return $select_option ;
}


function FormFieldsetMaker($form, $name, $url, $legend, $border){
	global $log  ; 
	$log = $log . "<b>" . __METHOD__  . "</b> (Form " . $name . ")<br> " ;
	$s1="margin-bottom:15px;" ; $s2="";
	if($border){$s2="border:1px solid black;";}
	$style = "style='" . $s1 . $s2 . "'" ;
	$form = 
		T6 . "<form action='" . $url . "' name='" . $name . "' method='post'>\n" .
		T7 .	"<fieldset " . $style . ">\n" .
		T8 .		"<legend>" . $legend . "</legend>\n" .
		T8 . 		"<ul class='formlist'>\n" .
					$form .
		T8 .		"</ul>\n" .
		T8 . 	"<div class='porte_SubmitButton'>\n" .
		T9 . 		"<input type='submit' class='OKButton'>\n" .
		T8 . 	"</div>\n" .
		T6 . "</fieldset></form>\n" ;
	return $form ;
}

function JSButtonAction($optionlinks ){
	$NewButton = "function NewButton(){
		var id_link = document.getElementById('id_ext_link') ;
		id_link.value=0 ;

		var name_label_field = document.getElementById('ext_link_name') ;
		name_label_field.value='Google' ;

		var name_label_url = document.getElementById('ext_link_url') ;
		name_label_url.value = 'http://google.com' ;

	}";

	$EditButton = "function EditButton(){
		var select=document.getElementById('ext_links') ;
		var option_value = select.options[select.selectedIndex].value;

		var id_link = document.getElementById('id_ext_link') ;
		id_link.value=select.options[select.selectedIndex].dataset.id ;

		var name_label_field = document.getElementById('ext_link_name') ;
		name_label_field.value=select.options[select.selectedIndex].dataset.name ;

		var name_label_url = document.getElementById('ext_link_url') ;
		name_label_url.value = select.options[select.selectedIndex].dataset.url ;

	}";

	$DeleteButton = "function DeleteButton(){
		var select=document.getElementById('ext_links') ;
		var option_value = select.options[select.selectedIndex].value;

		var id_link = document.getElementById('id_ext_link') ;
		id_link.value=select.options[select.selectedIndex].dataset.id ;

		var name_label_field = document.getElementById('ext_link_name') ;
		name_label_field.value=select.options[select.selectedIndex].dataset.name ;

		if(confirm('Do you really want to delete ' + name_label_field.value)){
			document.forms[0].submit();
		}
	}";

	return "<script>" . $NewButton . $EditButton . $DeleteButton . "</script>\n" ;
}
			//T6 . "<input type='hidden' name='id_link' value=''>\n" .
		//var option_text = select.options[select.selectedIndex].textContent ;


?>