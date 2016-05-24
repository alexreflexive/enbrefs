<?php
class htmlArticle {
	private $nav_article ;
	private $links_out ;
	private $links_in ;

	function get_article($article, $member, $user){
		global $log, $language ;($language) ;
		$log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		if($article){
			$h1 = T5 . "<h1>" . $article['title'] . "</h1>\n" ;
			$writer =
				"<a href='index.php?mode=fwriter&id_fwriter=" . 
					$member['id'] . "'>" . $member['pseudo'] . "</a>" ;

			$article_handlers = $this->get_ArticleHandlers($user, $article) ;

			$article_attributes = 
				T6 ."<div class='article_attributes'>\n" . 
					T7 . "<p>" . $language[PUBLISHED_BY] . $writer . "</p>\n" .
					T7 . "<p>" . $language[PUBLISH_DATE] . $article['published'] . "</p>\n" .
					T7 . "<p>" . $language[LAST_MODIF] . $article['modified'] . "</p>\n" .
						$article_handlers .
				T6 . "</div>\n" ;
			$abstract = "<div style='font-weight:bold;font-size:1.1em;'>" . $article['abstract'] . "</div>" ;
			$text = "<div id='article_text'>" . prepare_pour_afficher($article['text']) . "</div>\n" ;
			// La ligne suivante était pour faire une lettrine
			$text = preg_replace("/^([a-zA-Z0-9])/", "<div class='drop'>\\1</div>", $text) ;

			$return = $h1 . $article_attributes . $abstract . $text ;
			return $return ;
		}else {
			$log = $log . "return false<br>" ;
			return false ;
		}
	}

	function get_ArticleHandlers($user, $article){
		global $log, $language ;
		$log = $log . "<b>" .  __METHOD__  . "</b><br>" ; 
		//var_dump($article) ;

		$user_privilege = $user->get_Privilege() ;

		if($user_privilege >= WRITER){
			$post_handlers = 
				T8 . "<li><a href='index.php?mode=article&article=form_content&id_article=0&id_parent=" . $article['id'] . "'>" . 
					$language[ADD] . "</a></li>\n" ;

			if($article['id_writer'] == $user->get_IdMember() ||
				$user_privilege == ADMIN){
				$post_handlers = $post_handlers .
					T8 . "<li><a href='index.php?mode=article&article=form_content&id_article=" . 
						$article['id'] ."'>" . $language[MODIFY] . "</a></li>\n" ;
				}
			if($user_privilege == ADMIN){
				if($article['id_first']==0 && $article['id_last']==0){
					$remove_link =
						T8 . "<li><a href='index.php?mode=article&article=delete&id_article=" . $article['id'] . "'>" . $language[REMOVE] . "</a></li>\n" ;
					}else{
						$remove_link = T8 . "<li><a class='disabled'>" . $language[REMOVE] .  "</a></li>\n";
					}
				$post_handlers = $post_handlers . $remove_link ;
				}
			$post_handlers = 
				T7 . "<div class='post_handlers'><ul>\n" .
					$post_handlers .
				T7 . "</ul></div>\n" ;

		}else{ $post_handlers = "&nbsp" ;}
	return $post_handlers ;		
	}

	function get_Plan($scan){
		$plan = "" ;
		$h1 = "<h1>Plan du site</h1>\n" ;
		foreach($scan as $id => $record){
			$margin = $record['level'] * 15 ;
			$style = "style='margin-left:" . $margin . "px;'" ;
			$plan = $plan . "<li><a href='index.php?id_article=" . $record['id'] . "' " . $style . ">" . $record['name'] . "</a></li>\n" ;
		}
		$plan= "<ul>\n" . $plan . "</ul>\n" ;
		return $h1 . $plan ;
	}

	function get_ReservedArticles(){
		$h1="<h1>Article réservé</h1>" ;
		$p = "<p>Cet article contient un identifiant qui le rend réservé aux besoins de l'application. Si vous n'êtes pas développeur, vous ne devriez pas voir cette page.</p>" ;
		return $h1 . $p ;
	}
}
?>