<?php

class Navgenerator{
	private $user ;
	private $SetOfLinks ;

	function Navgenerator($user, $sol){
		global $log, $language ; $log = $log .  "<b>" .  __METHOD__  . "</b><br>" ;
		$this->user=$user ;
		$this->SetOfLinks=$sol ;
	}

	function get_FrontendMenu(){
		global $log, $language ; 
		$log = $log .   "<b>" . __METHOD__  . "<b><br>" ;
		$menu="" ;

		if($this->user->get_Privilege() == VISITOR){
			$log_in_out = $language[LOGIN] ;
			$form = URL_FORM_LOGIN ;
		}

		$menu =
		"<li><a href='" . URL_HOMEPAGE . "' class='bottom-align'>" . $language[HOME] . "</a></li>\n" .
		"<li><a href='" . URL_PLAN . "' class='bottom-align'>Plan</a></li>\n" ;
		
		if($this->user->get_Privilege() >= MEMBER){
			$log_in_out = $language[LOGOUT] . "[" . $this->user->get_Pseudo() . "]" ;
			$form = URL_LOGOUT;
			$menu = $menu .
				"<li><a href='index.php?mode=profile&profile=ownprofile' class='bottom-align'>" . $language[PROFILE] . "</a></li>\n" ;
		}
		/*
		if($this->user->get_Privilege() >= WRITER){
			$menu = $menu .
				"<li><a href='" . URL_EXPO_ARTICLE_FWRITER . "&id_writer=" . 
					$this->user->get_IdMember() . "' class='bottom-align'>" . $language[MY_ARTICLES] . "</a></li>\n" ;
		}

		if($this->user->get_Privilege() >= CHIEF_EDITOR){
			$menu = $menu .
				"<li><a href='" . URL_BACKEND_RANGE . "' class='bottom-align'>" . $language[RANGE] . "</a></li>\n" ;
		}
		*/
		
		if($this->user->get_Privilege() >= ADMIN){
			$menu = $menu .
				"<li><a href='" . URL_BACKEND_MAIN_ADMIN  . "' class='bottom-align'>" . $language[ADMIN] . "</a></li>\n" ;
		}
		//$form = "" ;
		$menu =
		"<ul id='menu' class='bottom-align'>\n" .
			$menu .
			"<li><a href='" . $form . "' class='bottom-align'>" . $log_in_out . "</a></li>\n" .
		"</ul>\n" .
		"<div id='menu_right'>\n" . 
			"<form action='" . URL_ARTICLE_SEARCH . "' " .
				"name='search' method='post' id='search_form'>\n" . 
				"<input type='text' placeholder='Search' id='search_txt'>\n" . 
				"<input type='submit' value='OK' id='search_submit'>" . 
			"</form>" . 
		"</div>\n" ;
		return $menu ;
	}

	function get_SurferFormMenu($submode){
		global $log, $language ;
		$log = $log .  "<b>" .  __METHOD__  . "</b><br>" ;
		
		$visitor =
			"<li><a href='" . URL_INDEX . "' class='bottom-align'>" . $language[HOME] . "</a></li>\n" ;
		
		$login = 
			"<li><a href='index.php?mode=surfer&surfer=login' class='bottom-align'>" . $language[LOGIN] . "</a></li>\n" ;

		$regin = 
			"<li><a href='index.php?mode=surfer&surfer=register' class='bottom-align'>" . $language[REGIN] . "</a></li>\n" ; 

		$HomeMenu =  	"<ul id='menu' class='bottom-align'>\n" . $visitor . "</ul>" ;
		$LoginMenu = 	"<ul id='menu' class='bottom-align'>\n" . $visitor . $regin . "</ul>" ;
		$RegisterMenu = 	"<ul id='menu' class='bottom-align'>\n" . $visitor . $login . "</ul>" ;

		//$ForgottentPassMenu = 	"<ul id='menu' class='bottom-align'>\n" . $visitor . $register . $login . "</ul>" ;

		$log = $log . "switch form<br>" ;
		switch($submode){
			case 'login':
			$menu = $LoginMenu ;
			$log = $log . "case login<br>" ;
			break;

			case 'register':
			$menu =$RegisterMenu ;
			$log = $log . "case regin<br>" ;
			break;

			case 'result_tryregin' :
			$menu = $HomeMenu ;
			$log = $log . "case result_tryregin<br>" ;
			break ;

			//case 'forgotten_pwd':
			//$menu = $ForgottentPassMenu ;
			//break;

			default:
			$menu="get_SurferFormMenu : Une erreur a dû se produire.<br>" ;
			break;
			}
			//echo $menu ;
			return $menu ;
	}

	function get_NavArticles(){
		global $log, $language ;
		$log = $log .  "<b>" .  __METHOD__  . "</b><br>" ;
		$n="" ;

		if(!empty($this->SetOfLinks['parent'])){
			$n =
				T5 . "<h1>" . $language[NAV_UP] . "</h1>\n" .
				T6 . "<ul>\n" .
				T7 .	"<li><a href='index.php?id_article=" . key($this->SetOfLinks['parent']) . "'>" . 
						current($this->SetOfLinks['parent']) . "</a></li>\n" .
				T6 . "</ul>\n" ;
		}

		if(!empty($this->SetOfLinks['brothers'])){
			$b="" ;
			foreach ($this->SetOfLinks['brothers'] as $key=>$sol){
				$b = $b . T7 . "<li><a href='index.php?id_article=" . $key  . "'>" . $sol . "</a></li>\n" ;
			}
			$n = $n . T5 . "<h1>" . $language[NAV_ASIDE] . "</h1>\n" . T6 . "<ul>\n" . $b . "</ul>" ; 
			$b="" ;
		}

		if(!empty($this->SetOfLinks['children'])){
			$c="" ;
			foreach ($this->SetOfLinks['children'] as $key=>$sol){
				$c = $c . T7 . "<li><a href='index.php?id_article=" . $key  . "'>" . $sol . "</a></li>\n" ;
			}
			$n = $n . T5 . "<h1>" . $language[NAV_DOWN] . "</h1>\n" . T6 . "<ul>\n" . $c . "</ul>" ; 
			$c="" ;
		}
		return $n ;
	}

	function get_LinkCollectionExpo(){
		global $log, $language ;
		
		$log = $log .   __METHOD__  . "<br>" ;
		$n="" ;
		if(!empty($this->SetOfLinks['ext_links'])){
			$b="" ;
			foreach ($this->SetOfLinks['ext_links'] as $key=>$sol){
				$b = $b . T7 . "<li><a href='" . $sol['url'] . "' target='_blank'>" . $sol['name'] . "</a></li>\n" ;
			}
			$n = $n . T5 . "<h1>" . $language[LINKS_OUT] . "</h1>\n" . T6 . "<ul>\n" . $b . "</ul>" ; 
			$b="" ;
		}

		if(!empty($this->SetOfLinks['int_links'])){
			$b="" ;
			foreach ($this->SetOfLinks['int_links'] as $key=>$sol){
				$b = $b . T7 . "<li><a href='index.php?id_article=" . $key . "'>" . $sol . "</a></li>\n"  ;
			}
			$n = $n . T5 . "<h1>" . $language[LINKS_IN] . "</h1>\n" . T6 . "<ul>\n" . $b . "</ul>" ; 
			$b="" ;
		}
		return $n ;
	}


/* --------------------------------------------- */
/* -------------- BACKOFFICE ------------ */
/* --------------------------------------------- */



	function get_MenuBackoffice(){
		global $log, $language ; $log = $log .  "<b>" .  __METHOD__  . "</b><br>" ;

	$backtofrontend =
		T5. "<ul id='menu' class='bottom-align'>\n" .
		T6 . 	"<li><a href='index.php' class='bottom-align'>\n" .
		T7 . 		$language[SHOW_SITE] . "\n" .
		T6 . 	"</a></li>\n" .
		T6 . 	"<li><a href='index.php?mode=logout' class='bottom-align'>" .
		T7 .		$language[LOGOUT] . "[" . $this->user->get_Pseudo() . "]\n" .
		T6 . 	"</a></li>\n" .		
		T5 . "</ul>\n" ;
	//echo $backtofrontend ;
	return $backtofrontend ;
	}

	function get_NavBackoffice($id, $mode){
		global $log, $language ; $log = $log .  "<b>" .  __METHOD__  . "</b><br>" ;

			$navigation =  T6 . "<li><a href='index.php?mode=profile&profile=ownprofile'>". $language[MY_PROFILE] . "</a></li>\n" ;
			//if($id_privilege>=CHIEF_EDITOR){
				//$navigation = $navigation . 
					//T6 . "<li><a href='index.php?backend=range'>" . $language[MY_RANGE] . "</a></li>\n" ;
			//}
			if($id['id_privilege'] >= ADMIN) {
				if (isset($id['id_article'])){$link_article = "&id_article=" . $id['id_article'] ;}
				else{$link_article="";}
				
				if($mode=='article'){
					$nav_articles = 
					T5 . "<ul style='font-size:0.8em;margin-left:15px;'>" .
						T6 . "<li><a href='index.php?mode=article&article=form_content" . $link_article . "'>Edition contenu</a></li>\n" .
						T6 . "<li><a href='index.php?mode=article&article=form_parent" . $link_article . "'>Edition parent</a></li>\n" .
						T6 . "<li><a href='index.php?mode=article&article=form_brother" . $link_article . "'>Edition frère</a></li>\n" .
						T6 . "<li><a href='index.php?mode=article&article=editliensinternes" . $link_article . "'>Edition liens internes</a></li>\n" .
						T6 . "<li><a href='index.php?mode=article&article=editliensexternes" . $link_article . "'>Edition liens externes</a></li>\n" .
					T5 . "</ul>\n" ;
					//echo "nav articles " . $nav_articles . "<br>" ;
				}else{$nav_articles="";}

				$navigation = $navigation . 
					T6 . "<li><a href='index.php?mode=monitoring'>" . $language[ARTICLE_MONITORING] . "</a></li>\n" .			
					T6 . "<li><a href='index.php?mode=article&article=edit" . $link_article . "'>" . $language[ARTICLE_EDIT] . "</a></li>\n" .	
						$nav_articles .	
					T6 . "<li><a href='index.php?mode=images'>" . "Images" . "</a></li>\n " .
					T6 . "<li><a href='" . URL_BACKEND_MAIN_ADMIN . "'>" . $language[ADMIN] . "</a></li>\n" .			
					T6 . "<li><a href='index.php?mode=memberlist&memberlist=form'>" . $language[MEMBERLIST] . "</a></li>\n" ;
			} 

			$messenger =  T6 . "<li><a href='index.php?mode=messenger&messenger=inbox'>" . $language[MESSENGER] . "</a></li>\n" ;
			if($mode=="messenger"){
				$messenger =
					$messenger .
					T5 . "<ul style='font-size:0.8em;margin-left:15px;'>" .
						T6 . "<li><a href='index.php?mode=messenger&messenger=inbox'>" . $language[INBOX] . "</a></li>\n" .
						T6 . "<li><a href='index.php?mode=messenger&messenger=sentbox'>" . $language[SENTBOX] . "</a></li>\n" .
					T5 . "</ul>\n" ;
			}
			$navigation = 
				T5 . "<ul>\n" .
					$navigation .
					$messenger .
				T5 . "</ul>\n" ;
		return $navigation ;
	}	
}
/*
}function get_NavBackoffice($id_privilege, $mode, $id_article){
		global $log, $language ; $log = $log .  "<b>" .  __METHOD__  . "</b><br>" ;

			$navigation =  T6 . "<li><a href='index.php?mode=profile&profile=ownprofile'>". $language[MY_PROFILE] . "</a></li>\n" ;
			//if($id_privilege>=CHIEF_EDITOR){
				//$navigation = $navigation . 
					//T6 . "<li><a href='index.php?backend=range'>" . $language[MY_RANGE] . "</a></li>\n" ;
			//}
			if($id_privilege >= ADMIN) {
				if (isset($id['id'])){$link_article = "&id_article=" . $id['id'] ;}
				else{$link_article="";}
				
				if($backend['name']=='article'){
					$nav_articles = 
					T5 . "<ul style='font-size:0.8em;margin-left:15px;'>" .
						T6 . "<li><a href='index.php?article=form_content" . $link_article . "'>Edition contenu</a></li>\n" .
						T6 . "<li><a href='index.php?article=form_geography" . $link_article . "'>Edition géographie</a></li>\n" .
					T5 . "</ul>\n" ;
					//echo "nav articles " . $nav_articles . "<br>" ;
				}else{$nav_articles="";}

				$navigation = $navigation . 
					T6 . "<li><a href='index.php?mode=monitoring'>" . $language[ARTICLE_MONITORING] . "</a></li>\n" .			
					T6 . "<li><a href='index.php?article=form" . $link_article . "'>" . $language[ARTICLE_EDIT] . "</a></li>\n" .	
						$nav_articles .		
					T6 . "<li><a href='index.php?admin=form'>" . $language[ADMIN] . "</a></li>\n" .			
					T6 . "<li><a href='index.php?listmembers=form'>" . $language[MEMBERLIST] . "</a></li>\n" ;
			} 

			$messenger =  T6 . "<li><a href='index.php?mode=messenger&messenger=inbox'>" . $language[MESSENGER] . "</a></li>\n" ;
			if($mode=="messenger"){
				$messenger =
					$messenger .
					T5 . "<ul style='font-size:0.8em;margin-left:15px;'>" .
						T6 . "<li><a href='index.php?mode=messenger&messenger=inbox'>" . $lang['fr'][INBOX] . "</a></li>\n" .
						T6 . "<li><a href='index.php?mode=messenger&messenger=sent'>" . $lang['fr'][SENTBOX] . "</a></li>\n" .
					T5 . "</ul>\n" ;
			}
			$navigation = 
				T5 . "<ul>\n" .
					$navigation .
					$messenger .
				T5 . "</ul>\n" ;
		return $navigation ;
	}	
	*/
