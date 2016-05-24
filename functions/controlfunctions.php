<?php
function http_extract($request, $session, $mabd){
	global $language, $log ; $log = $log . "<b>" .  __FUNCTION__  . "</b><br>" ;
	ob_start() ;echo "request : " ; var_dump($request) ; echo "<br>" ; $buffer1 =  ob_get_clean() ; //ob_get_contents() ; ob_end_clean() ;
	ob_start() ; echo "session : " ; var_dump($session) ; echo "<br>" ; $buffer2 = ob_get_clean() ; // ob_get_contents() ; ob_end_clean() ;
	$log = $log . $buffer1 . $buffer2 ; 
//echo __FILE__ . " : " . __LINE__ . " : " ; var_dump($request) ; echo "<br><br>" ;

	if(!isset($request['mode']) || $request['mode']==""){
		$log = $log . "request[mode] n est pas initialisé ou est vide.<br>" ;
		$mode = 'article' ;
		$$mode = 'show' ;
	}else{
		$log = $log . "else request[mode] n est pas initialisé ou est vide.<br>" ;
		$mode=$request['mode'] ;
		if(isset($request[$mode])){
			$$mode=$request[$mode] ;
		}else{$$mode="";}
	}
	$log = $log . "mode = " . $mode . " et double mode = " . $$mode . "<br>" ;
	$user = get_User($session, $mabd) ;
	$set_of_links ="" ;
	require_once('classes/nav.html.class.php');
	switch($mode){

		case 'logout':
		$log = $log . "switch case mode==logout<br>" ;
		$_SESSION=array();
		session_destroy();
		$session="";
		header('location:index.php');
		break ;

		case 'surfer':
		$log = $log . "switch case mode==surfer<br>" ;
		require_once('functions/surferforms.php');
		switch(${$mode}){

			case 'register':
			$log = $log . "switch case mode==surfer switch case mode-mode==regin<br>" ;
			$article = ReginForm() ;  
			break;

			case 'tryregin':
			$log = $log . "switch case mode==surfer switch case mode-mode==rec_regin<br>" ;
			$surfer=array(
				'id'=>0 ,
				'pseudo'=>$request['pseudo'] ,
				'email'=>$request['email'] ,
				'password'=>$request['password'] ,
				'id_privilege'=>MEMBER ,
				'devise'=>"" ,
				'language'=>'en');
			$user=$mabd->CreateUser($surfer) ;
			if($mabd->create_member($surfer)){
				$article = MessageOutPut(1) ;  
			}else{
				$article = MessageOutPut(2) ;  
			}
			$$mode='result_tryregin';
			break ;

			case 'login':
			$log = $log . "switch case mode==surfer switch case mode-mode==login<br>" ;
			$article = LoginForm() ;
			break;

			case 'testlogin':
			$log = $log . "switch case mode==surfer switch case mode-mode==try_login<br>" ;
			$post=array('pseudo'=>$request['pseudo'], 'password'=>$request['password']) ;
			if($user=$mabd->ValidateLogin($post) ){	// ce user est un tableau, pas un objet
				$log = $log . "Le login est valide<br>" ;
				$_SESSION = $user ;
				header('location:index.php');
			}else{
				$log = $log . "Le login n est pas valide<br>" ;
				$article = LoginForm() ; 
				$$mode = 'login' ;
			}
			break;

			case 'forgotten_pwd':
			$log = $log . "switch case mode==surfer switch case mode-mode==forgotten_pwd<br>" ;
			//code
			break;

			case 'test_forgotten_pwd':
			$log = $log . "switch case mode==surfer switch case mode-mode==test_forgotten_pwd<br>" ;
			//code
			break;

			default:
			$log = $log . "switch case mode==surfer switch case mode-mode==default<br>" ;
			break;
		}
		$output = FRONTEND ;
		break;

		case 'article':
		$log = $log . "switch case mode==article<br>" ;
		if(isset($request['id_article'])){$id_article = $request['id_article'] ;} // penser à mettre une valeur par défaut
		elseif(isset($session['id_current_article'])){$id_article = $session['id_current_article'] ;}
		elseif(${$mode}=='show' || ${$mode}=='edit'){$id_article =  HOMEPAGE;}
		else{$log = $log . "id article attribution problem (line " . __LINE__ . ".<br>";}
		if(!empty($_SESSION)){$_SESSION['id_current_article']=$id_article ;}

		switch(${$mode}){
			case 'show':
			$log = $log . "switch case mode==article switch case mode-mode==show<br>" ;
			require_once('classes/article.html.class.php');
			$a = new htmlArticle ;
			if($id_article==PLAN){
				$log = $log . "id article = PLAN<br>" ;
				$article=$a->get_Plan($mabd->article_monitoring()) ;
			}elseif($id_article>=ARTICLE_03 && $id_article<=ARTICLE_10){
				$log = $log . "id article = Article réservé 03 à 10<br>" ;
				$article=$a->get_ReservedArticles() ;
			}else{
				$log = $log . "else à id article = PLAN<br>" ;
				$tab_article = $mabd->get_article($id_article) ;
				$tab_writer = $mabd->get_member($tab_article['id_writer']) ;
				$article=$a->get_article($tab_article, $tab_writer, $user) ;
			}
			$output = FRONTEND ;
			$set_of_links = $mabd->get_set_of_links($id_article) ;
			break;

			case 'preview':
			if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==preview<br>" ;
			//echo "je suis à preview<br>";
			$article=array();
			$article['preview']=true ;
			$article['id']=$request['id_article'] ;
			$article['id_parent']=$request['id_parent'] ;
			$article['published']=$request['published'] ;
			$article['modified']=$request['modified'] ;
			$article['name']=$request['name'] ;
			$article['title']=$request['title'] ;
			$article['id_writer']=$request['id_writer'] ;
			$article['id_status']=$request['id_status'] ;
			$article['abstract']=$request['abstract'] ;
			$article['text']=$request['text'] ;
			require_once('functions/backendforms.php') ;
			$preview=true ;
			$article = get_EditArticleContent($article) ;
			$output=BACKEND;
			break;

			case 'edit':
			if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==edit<br>" ;
			$scan = $mabd->article_monitoring() ;
			require_once('functions/backendforms.php') ;
			$article =  get_ArticleForm($id_article, $scan) ;
			$output=BACKEND;
			break;

			case 'form_content':
			if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==form_content<br>" ;
			if(isset($request['id_parent'])){$id_parent=$request['id_parent'];}
			if($id_article==0){
				$article=Article::get_DefaultArticle($id_parent) ;
			}else{
				$article = $mabd->get_article($id_article) ;
			}
			require_once('functions/backendforms.php') ;
			$preview=false;
			$article = get_EditArticleContent($article) ;
			$output=BACKEND;
			break;

			case 'rec_content':
			if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==rec_content<br>" ;
			//var_dump($request);
			$content = array(
					'id'=>$request['id_article'] ,
					'id_parent'=>$request['id_parent'] ,
					'title'=>$request['title'] ,
					'abstract'=>$request['abstract'] ,
					'name'=>$request['name'] ,
					'id_status'=>$request['id_status'] ,
					'text'=>$request['text'] ,
					'id_writer'=>$user-> get_IdMember()) ;
			if($id_article==0){
				$log = $log .  "On va enregistrer un nouvel article (on est dans http extract).<br>" ;
				if($id_article=$mabd->rec_new_article($content)){
					$log = $log .  "enregistrement de nouvel article.<br>" ;
					$msg="L'article a bien été enregistré." ;
					$_SESSION['id_current_article']=$id_article ;
				}else{
					$log = $log . "échec de l enregistrement de nouvel article.<br>";
					$msg="L'enregistrement de l'article a échoué." ;
				}
			}else{
				$log = $log .  "On va enregistrer une modification d article (on est dans http extract).<br>" ;
				if($mabd->rec_article_content($content)){
					$log = $log .  "enregistrement d un article existant.<br>" ;
					$msg = "La modification a été prise en compte." ;
				}else{
					$log = $log . "échec de l enregistrement article existant.<br>" ;
					$msg = "Echec de la modification." ;
				}
			}
			$article = $mabd->get_article($id_article) ;
			require_once('functions/backendforms.php') ;
			$article = get_EditArticleContent($article, $msg) ;
			$output=BACKEND;
			break;

			case 'form_parent':
			case 'form_brother':
			if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==form_parent/brother<br>" ;
			if(${$mode}=='form_parent'){$family=1;}elseif(${$mode}=='form_brother'){$family=2;}
			$scan = $mabd->article_monitoring() ;
			require_once('functions/backendforms.php') ;
			$article = get_EditArticleParent($id_article, $scan, $family) ;
			$output=BACKEND;
			break;

			case 'rec_parent':
			case 'rec_brother':
			if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==rec_geography<br>" ;

			if(${$mode}=='rec_parent'){
				$id_new_parent = $request['id_from_scandisplay'] ;
				$id_current = $request['id_article'] ;
				$family=1;
				if($mabd->change_parent($id_current, $id_new_parent)){
					$log = $log . "Le déplacement chez un nouveau parent s'est bien passé.<br>";
				}else{
					$log = $log . "Le déplacement chez un nouveau parent s'est mal passé.<br>";
				}
			}elseif(${$mode}=='rec_brother'){
				$family=2;
				$id_new_bigbrother = $request['id_from_scandisplay'] ;
				$id_current = $request['id_article'] ;
				if($mabd->change_brother($id_current, $id_new_bigbrother)){
					$log = $log . "Le déplacement chez un nouveau frère s'est bien passé.<br>";
				}else{
					$log = $log . "Le déplacement chez un nouveau frère s'est bien passé.<br>";
				}
			}
			$scan = $mabd->article_monitoring() ;
			require_once('functions/backendforms.php') ;
			$article = get_EditArticleParent($id_article, $scan, $family) ;
			$output=BACKEND;
			break;

			case 'editliensinternes' :
			//if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==editliensinternes<br>" ;
			if(isset($request['delete'])){
				//var_dump($request) ;
				$mabd->remove_int_link($id_article, $request['delete']) ;
			}
			elseif(isset($request['record'])){			
				//var_dump($request) ;
				//echo "id article : $id_article" ;
				$link = array(
					'id_article' => $id_article,
					'id_linked_article'=>$request['int_links']) ;
				$mabd->create_int_link($link) ;
			}
			$scan = $mabd->article_monitoring() ;
			$links = $mabd->get_int_links($id_article) ;
			require_once('functions/backendforms.php') ;
			$article = get_InternalLinks($scan, $links) ;
			$output=BACKEND;
			break;

			case 'editliensexternes':
			if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==editliensexternes<br>" ;
			if(isset($request['delete'])){
				$mabd->remove_ext_link($id_article, $request['id_ext_link']) ;
			}
			elseif(isset($request['record'])){
				//echo "enregistrement:";
				if($request['id_ext_link']==0){
					//echo "nouveau lien<br>";
					$link=array(
							'id_article'=>$session['id_current_article'],
							'name'=>$request['ext_link_name'],
							'url'=>$request['ext_link_url']
							) ;
					$mabd->create_ext_link($link) ;
				}else{
					//echo "modification de lien<br>" ;
					//var_dump($request) ;
					$link=array(
							//'id_article'=>$session['id_current_article'],
							'id_link'=>$request['ext_links'],
							'name'=>$request['ext_link_name'],
							'url'=>$request['ext_link_url']
							) ;
					$mabd->modify_ext_link($link) ;
				}
			}
			$optionlinks = $mabd->get_ext_links($id_article) ;
			if(isset($request['id_ext_link'])){
				$currentlink=$request['id_ext_link'];
			}else{
				$currentlink=false;
			}
			$links=array(
				'id_current_link'=>$currentlink,
				'optionlinks'=>$optionlinks) ;
			require_once('functions/backendforms.php') ;
			$article = get_ExternalLinks($links) ;
			$output=BACKEND;
			break;

			case 'delete' :
			if(empty($session)){header('location:index.php');}
			$log = $log . "switch case mode==article switch case mode-mode==delete<br>" ;
			$tab_article = $mabd->get_article($id_article) ;
			if($tab_article['id_first']==0 || $tab_article['id_last']==0){
			
				if($id_parent = $mabd->del_article($id_article)){
					$_SESSION['id_current_article'] = $id_parent ;
				} 
					
			}
			header('location:index.php');
			break ;

			case 'article_msg':
			$log = $log . "switch case mode==article switch case mode-mode==article_msg<br>" ;
			break;

			default:
			$log = $log . "switch case mode==article switch case mode-mode==default<br>" ;
			$log = $log . "Pas de sousmode d'article repertorié<br>" ;
			break;
		}
		break ;

		case 'fwriter':
		$log = $log . "switch(pilote[mode]) fwriter<br>" ;
		$preferences = $mabd->get_Preferences() ;						///////////////////////////////////////
		if(isset($request['page'])){$page=$request['page'];}
		else{$page=1;}
		if(isset($request['id_fwriter'])){$id_fwriter=$request['id_fwriter'];}
		$filtre = $mabd->get_articles_FilterAutor(
						$id_fwriter, 
						$page, 
						$preferences['rows_per_page'] ) ;
		require('recherches.php') ;
		$article = filtre_Article($filtre) ;
		$output=FRONTEND;
		break;

		case 'search':
		$log = $log . "switch(pilote[mode]) search<br>" ;
		$preferences = $mabd->get_Preferences() ;
		if(isset($request['page'])){$page=$request['page'];}
		else{$page=1;}
		$filtre = $mabd->get_articles_search(
						$page, 
						$preferences['rows_per_page'] ) ;
		require('recherches.php') ;
		$article = search_Article($filtre) ;
		$output=FRONTEND;
		break;

		case 'profile':
		$log = $log . "switch case mode==profile<br>" ;
		if($session['id_privilege']<MEMBER){header('location:index.php');}
		require_once('backendforms.php') ;
		if(isset($request['id_member'])){$id_member=$request['id_member'];}
		$msg="" ;
		$output=BACKEND;
		switch(${$mode}){

			case 'ownprofile' :
			$consultant = AUTOCONSULTANT ;
			break ;

			case 'userprofile':
			$consultant = SUPERCONSULTANT ;
			break ;

			case 'recpersonalprofile' :
			$log = $log . "recpersonalprofile<br>" ;
			$surfer=array(
				'id'=>$request['id'],
				'pseudo'=>$request['pseudo'] ,
				'email'=>$request['email'] ,
				'id_privilege'=>$request['id_privilege']
				 );
			$consultant = $request['consultant'] ;
			if($newpersonalprofile = $mabd->modify_personalprofile($surfer)){
				$msg = "Les donnees personnelles ont bien été mis à jour." ;
			}else{$msg="Problème lors de la mise à jour des donnees personnelles.";}
			break ;

			case 'recsecretprofile' :
			$log = $log . "recsecretprofile<br>" ;
			$surfer=array(
				'id'=>$request['id'] ,
				'old_password'=>$request['current_password'] ,
				'new_password'=>$request['new_password'] ,
				'confirm_password'=>$request['confirm_password'] 
				);
			if($surfer['new_password']==$surfer['confirm_password']){
				if($mabd->CheckPassword($surfer['id'], $surfer['old_password'])){
					if($mabd->modify_secretprofile($surfer)){
						$msg = "La mise à jour du mot de passe a été effectuée." ;
					}else{$msg="La mise à jour du mot de passe a échoué.";}
				}else{$msg="Le mot de passe courant n est pas correct.";}
			}else{$msg="Le mot de passe et le mot de passe de confirmation sont différents.";}
			$consultant = $request['consultant'] ;
			break;

			case 'recsocialprofile' :
			$log = $log . "recsocialprofile<br>" ;
			$surfer['id']=$request['id'] ;
			$surfer['devise']=$request['devise'] ;
			$surfer['language']=$request['language'] ;
			if($mabd->modify_socialprofile($surfer)){
				$msg="La modification des données sociales a été effectuée." ;
			}else{$msg="La modification des données sociales a échoué." ;}
			$consultant = $request['consultant'] ;
			break;
		}

		if($consultant==AUTOCONSULTANT){
			$member = $mabd->get_member($user->get_IdMember()) ;
			$user =  new Internetuser($member) ;
			$member_profile = $user ;
			$_SESSION = $member ;
			$title = $language[TITLE_OWNPROFILE] ;
		}elseif($consultant==SUPERCONSULTANT){
			$member = $mabd->get_member($id_member) ;
			$member_profile = new Internetuser($member) ;
			$title = $language[TITLE_USERPROFILE]  . $member['pseudo'] ;
		}
		$article = get_FormProfile($title, $member_profile, $consultant, $msg) ;
		break;

		case 'monitoring':
		$log = $log . "switch case mode==monitoring<br>" ;
		if($session['id_privilege']<ADMIN){header('location:index.php');}
		$title_article = "<h1>Monitoring</h1>" ;
		$article = "<b>starting monitoring</b><br>" ;
		$article = $title_article . $article ;
		if($mabd->article_monitoring()){
			$article = $article . "Ici sont prévus des tests vérifiant l'intégrité des tables.<br>" ;
			$article = $article . "<b>End monitoring</b><br>" ;
		}else{$article = $article . "Problem with monitoring.<br>";}
		$output=BACKEND ;
		break;

		case 'images':
		$log = $log . "switch case mode==images<br>" ;
		if(empty($session)){header('location:index.php');}
		if(isset($_GET['directory'])){
			$current_dir=$_GET['directory'] ;
		}else{
			$current_dir=IMG_CONTENT;
		} 

		$show_files="" ;
		$js="";
		if(strlen($current_dir) == strrpos($current_dir, "/")+1){
			$log = $log . "Pas d'ajout de slash pour $current_dir <br>" ;
		}else{
			$log = $log . "Ajout de slash pour $current_dir <br>" ;
			$current_dir=$current_dir . "/" ;
		}

		if(!empty($request['new_dir'])){
			$log = $log . "New Directory<br>" ;
			mkdir($current_dir . $request['new_dir']) ;
		}elseif(isset($request['remove_dir'])){
			$log = $log . "Remove directory<br>" ;
			$files_in_dir = scandir($current_dir) ; 
			if(count($files_in_dir)==2){
				$parent= dirname($current_dir) ;
				rmdir($current_dir);
				$current_dir= $parent . "/" ;
			}
		}elseif(isset($request['remove_file'])){
			$log = $log . "Romove image file<br>" ;
			unlink($request['selected_file']) ;
		}elseif(isset($request['rename_file'])){
			$log = $log . "Rename image file<br>" ;
			rename($request['selected_file'], $request['new_name'] ) ;
		}
		elseif(!empty($_FILES)){
			$log = $log . "Upload image file<br>" ;
			move_uploaded_file($_FILES['nom_du_fichier']['tmp_name'], $current_dir.$_FILES['nom_du_fichier']['name']);
		}
		$files_in_dir = scandir($current_dir) ; 
		
		foreach($files_in_dir as $file){
			if(is_dir($current_dir . $file) && $file==".." && $current_dir !=IMG_CONTENT . "/"){
				$size =  "width=" . EXPLORER_IMG_SIDE . "px";
				$parent = dirname($current_dir) ;
				$figure = get_ImageFigure($parent, "Parent directory", ICON_PARENT_FOLDER, $size, $js) ;
				$show_files=$show_files . $figure ;
				$next_current_dir=$parent ;
			}elseif(is_dir($current_dir . $file) && $file!="." && $file!=".."){
				$size =  "width=" . EXPLORER_IMG_SIDE . "px";
				$show_files=$show_files .
					get_ImageFigure($current_dir  . $file, $file, ICON_FOLDER, $size, $js) ;
				$next_current_dir = $current_dir . $file ;

			}elseif(is_file($current_dir . $file) && $file!='dir_logo.jpg' && $file!="dir_parent.gif"){
				$img_info = getimagesize($current_dir . $file) ;
				if($img_info[HEIGHT]>$img_info[WIDTH]){
					$size = "height=" . EXPLORER_IMG_SIDE . "px";
				}else{
					$size = "width=" . EXPLORER_IMG_SIDE . "px";
				}
				$js="onclick=\"getFileName('" . $current_dir  . $file . "');\"" ;
				$show_files=$show_files .
					"<figure style='display:inline-block;'>\n" .
						"<a $js class='link_img'>\n" .
							"<img src='" . $current_dir  . $file . "' $size>\n" .
						"</a>\n" .
					"<figcaption>$file</figcaption></figure>\n"  ;
				$next_current_dir = $current_dir ; //<= A quoi ça sert ???
			}
		}
		require_once('backendforms.php') ;
		$article =get_Images($show_files, $current_dir) . get_js() ;
		$output=BACKEND ;
		break;

		case 'admin':
		$log = $log . "switch case mode==admin<br>" ;
		if($session['id_privilege']<ADMIN){header('location:index.php');}
		require_once('functions/backendforms.php') ;
		switch(${$mode}){

			case 'main_form' :
			$preferences = $mabd->get_Preferences() ;
			$article = get_Administration($preferences) ;
			break ;

			case 'rec_main_form':
			if(isset($request['echo_log'])){$echolog=1;}else{$echolog=0;}
			$preferences=array(
				"id"=>$request['id'] ,
				"name"=>$request['name'] ,
				"devise"=>$request['devise'] ,
				//"default_lang"=>$request['default_lang'] ,
				"rows_per_page"=>$request['rows_per_page'] ,
				"echo_log"=>$echolog ,
				//"language"=>$request['language'] ,
				"default_lang"=>$request['language'] ,
				"id_inscription"=>$request['id_inscription']) ;
			if($mabd-> modify_Preferences($preferences)){
				$log = $log . "Modification des préférences du site enregistrées.<br>";
			}else{$log = $log . "Echec de l'enregistrement des préférences du site.<br>" ;}
			$preferences = $mabd->get_Preferences() ;
			//var_dump($preferences) ;
			$article = get_Administration($preferences) ;
			break ;
		}
		$output=BACKEND ;
		break;

		case 'memberlist' :
		$log = $log . "switch case mode==memberlist<br>" ;
		if($session['id_privilege']<MEMBER){header('location:index.php');}
		$preferences = $mabd->get_Preferences() ;
		if(isset($request['page'])){$page = $request['page'];}
		else{$page=1;}
		$mc = $mabd->get_MemberCollection($page, $preferences['rows_per_page']) ;
		require_once('backendforms.php');
		$article = get_CollectionMembers($mc, $mc['pagination']) ;
		$output=BACKEND ;
		break ;

		case 'messenger':
		$log = $log . "switch case mode==messenger<br>" ;
		if($session['id_privilege']<MEMBER){header('location:index.php');}
		$preferences = $mabd->get_Preferences() ;
		require("classes/pm.html.class.php");
		$pm_html= new messenger ;
			switch($$mode){

			case 'inbox':
				$log = $log . "switch pilote backend case inbox<br>" ;
				if(isset($request['page'])){$page = $request['page'];}
				else{$page=1;}
				$msg="" ;
				$article = $pm_html->get_Inbox(
							$mabd->get_pm_inbox(
								$user->get_IdMember(),
								$page, 
								$preferences['rows_per_page']),
							$msg) ;
			break;

			case 'sentbox':
				$log = $log . "switch pilote backend case sent<br>" ;
				if(isset($request['page'])){$page = $request['page'];}
				else{$page=1;}
				$article = $pm_html->get_Sent(
							$mabd->get_pm_sent(
								$user->get_IdMember(),
								$page, 
								$preferences['rows_per_page'])) ;
			break;

			case 'sentpmshowbox':
				$log = $log . "switch pilote backend case sentpmshowbox<br>" ;
				if(isset($request['id_pm'])){$id_pm = $request['id_pm'];}
				$pm = $mabd->get_onepm_sent($id_pm) ;
				$article = 
					$pm_html->get_PMShowbox(
						$mabd->get_onepm_sent($id_pm), $user) ;
			break;

			case 'inpmshowbox':
				$log = $log . "switch pilote backend case inpmshowbox<br>" ;
				if(isset($request['id_pm'])){$id_pm = $request['id_pm'];}
				$pm = $mabd->get_onepm_in($id_pm) ;
				$article = 
					$pm_html->get_PMShowbox(
						$mabd->get_onepm_in($id_pm), $user) ;
			break;
/*
			case 'pmshowbox':
				$log = $log . "switch pilote backend case pmshowbox<br>" ;
				if(isset($request['id_pm'])){$id_pm = $request['id_pm'];}
				$pm = $mabd->get_private_message($id_pm) ;
				$article = 
					$pm_html->get_PMShowbox(
						$mabd->get_private_message($id_pm), $user) ;
			break;
*/
			case 'pmeditbox':
				$log = $log . "switch pilote backend pmedibox<br>" ;
				if(isset($request['id_recipient'])){$id_recipient=$request['id_recipient'];}
				$from_pseudo = $user->get_Pseudo() ;
				$to_pseudo =$mabd->get_member($id_recipient)['pseudo'] ;
				$new_pm=array('from_pseudo'=>$from_pseudo, 'to_pseudo'=>$to_pseudo, 'to_id'=>$id_recipient) ;
				$article = $pm_html->get_PMEditbox($new_pm) ;
			break;

			case 'rec_pm':
				$log = $log . "switch pilote messenger rec_pm<br>" ;
				$pm=array(
					'pm_title'=>$request['pm_title'] ,
					'pm_text'=>$request['pm_text'] ,
					'to_id'=>$request['to_id'] ,
					'to_pseudo'=>$request['to_pseudo'] ,
					'from_id'=>$user->get_IdMember() );
				if($id_pm = $mabd->rec_pm($pm)){
					$msg= "L'enregistrement s'est bien déroulé.<br>" ;
				}else{
					$msg = "il y a eu un problème à l'enregistrement.<br>" ;
				}
				var_dump($id_pm) ;
				$article = $pm_html->get_PMShowbox($mabd->get_onepm_sent($id_pm['senttable']), $user) ;
			break ;
/*
			case 'del_pm':
				$log = $log . "switch pilote messenger del_pm<br>" ;
				if(isset($request['id'])){$id_pm = $request['id'];}
				$page=1 ;
				if($mabd->del_pm($id_pm)){
					$msg="La suppression a bien été effectuée<br>" ;
				}else{
					$msg = "Il y a eu un problème los de la suppression.<br>" ;
				}
				$article = $pm_html->get_Inbox(
							$mabd->get_pm_inbox(
								$user->get_IdMember(),
								$page, 
								$preferences['rows_per_page']) ,
							$msg) ;
			break;
*/
			case 'del_sentpm':
				$log = $log . "switch pilote messenger del_pm<br>" ;
				if(isset($request['id'])){$id_pm = $request['id'];}
				$page=1 ;
				if($mabd->del_sentpm($id_pm)){
					$msg="La suppression a bien été effectuée<br>" ;
				}else{
					$msg = "Il y a eu un problème los de la suppression.<br>" ;
				}
				$article = $pm_html->get_Sent(
							$mabd->get_pm_sent(
								$user->get_IdMember(),
								$page, 
								$preferences['rows_per_page']) ,
							$msg) ;
			break;

			case 'del_inpm':
				$log = $log . "switch pilote messenger del_pm<br>" ;
				if(isset($request['id'])){$id_pm = $request['id'];}
				$page=1 ;
				if($mabd->del_inpm($id_pm)){
					$msg="La suppression a bien été effectuée<br>" ;
				}else{
					$msg = "Il y a eu un problème los de la suppression.<br>" ;
				}
				$article = $pm_html->get_Inbox(
							$mabd->get_pm_inbox(
								$user->get_IdMember(),
								$page, 
								$preferences['rows_per_page']) ,
							$msg) ;
			break;

			default :
				$log = $log . "switch pilote backend : error<br>" ;
				echo "dérapage!" ;
			break;
			}
		$output=BACKEND ;
		break;

		default:
			$log = $log . "Pas de mode connu (ligne " . __LINE__ . ").<br>" ;
		break;
	}

	$n=new Navgenerator($user,$set_of_links) ;
	if($output==FRONTEND){
		if($mode=='surfer'){
			$menu = $n->get_SurferFormMenu($$mode) ;
		}else{
			$menu = $n->get_FrontendMenu() ;
		}
		$navigation = $n->get_NavArticles() ;
		$external_links = $n->get_LinkCollectionExpo() ;
	}elseif($output==BACKEND){
		$menu = $n->get_MenuBackoffice() ;
		$id['id_privilege']=$user->get_Privilege() ;
		$navigation = $n->get_NavBackoffice($id, $mode) ;
		$external_links = "" ;
	}
	$display = get_Display() ;
	$pilote=array(
			'user'=>$user,
			'menu'=>$menu,
			'navigation'=>$navigation,
			'article'=>$article,
			'css'=>$display['css'][$output] ,
			'logo'=>$display['logo'][$output] ,
			'body_title'=>$display['body_title'][$output] ,
			'external_links'=>$external_links,
			'log'=>$log);
	return $pilote ;
}

function get_User($session, $mabd){
	global $log, $language ; $log = $log . "<b>" .  __FUNCTION__  . "</b><br>" ;
	if(empty($session)){
		$log = $log . "Session vide : utilisateur par défaut.<br>" ;
		$user=$mabd->CreateUser(Internetuser::get_DefaultUser()) ;
	}else{
		$log = $log . "Session initialisée : utilisateur de session.<br>" ;
		$user=$mabd->CreateUser($session);
		if($language[ABBREVIATION]!=$user->get_Language()){
			$lg=$user->get_Language() ;
			$language_file = "Langages/lang_" . $lg . ".php" ;
			require_once($language_file);
			$language = $lang[$lg] ;
		}
	}
	return $user ;
}

function get_ImageFigure($path, $caption, $logo, $size, $js){
	global $log, $language ; $log = $log . "<b>" .  __FUNCTION__  . "</b><br>" ;
	$image_figure = 
		"<figure style='display:inline-block;'>\n" .
			"<a $js href='index.php?mode=images&directory=" . $path . "' class='link_img'>\n" .
				"<img src='" . $logo . "' $size>\n" .
			"</a>\n" .
		"<figcaption>$caption</figcaption></figure>\n"  ;
	return $image_figure ;
}

function get_Display(){
	$display=array() ;
	$display['css'][FRONTEND]=
		"<link rel='stylesheet' type='text/css' href='css/styles.css'>\n" .
		"<link rel='stylesheet' type='text/css' href='css/codestyles.css'>\n" ;
	$display['css'][BACKEND]=
		"<link rel='stylesheet' type='text/css' href='css/backoffice.css'>".
		"<link rel='stylesheet' type='text/css' href='css/codestyles.css'>\n" ;

	$display['body_title'][FRONTEND]=BODY_TITLE_FRONT ;
	$display['body_title'][BACKEND]=BODY_TITLE_BACK;
	$display['logo'][FRONTEND]=LOGO_FRONT ;
	//$display['logo'][BACKEND]=LOGO_BACK;
	$display['logo'][BACKEND]="";
	return $display ;
}

function get_js(){
	return 
		"<script>" .
			"function getFileName(file){
				var txtRemoveFile=document.getElementById('selected_file') ;
				txtRemoveFile.value = file ;
			}" .
		"</script>";
}

?>
