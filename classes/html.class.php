<?php
class webpage {
	private $meta_title = "&nbsp" ;
	private $meta = "<meta Charset='UTF-8'>\n";
	private $style = "&nbsp" ;
	private $script ="&nbsp" ;

	private $header = "&nbsp";
	private $menu = "&nbsp";
	private $logo = "&nbsp";
	private $body_titre = "&nbsp" ;

	private $section = "&nbsp";
	private $nav = "&nbsp";
	private $article = "&nbsp" ;
	private $linkcollection = "&nbsp" ;

	private $footer = "&nbsp" ;

	function webpage($mt, $css, $js, $bt, $l){
		$this->meta_title = $mt ;
		$this->style = $css ;
		$this->script = $js ;
		$this->body_title = $bt ;
		$this->logo = $l ;
	}
	
	function show(){
		$this->set_Section() ;
		echo $this->get_html() ;
	}

	// Affichage principal
	function get_html(){
		return "<!DOCTYPE html>\n" .
		"<html>\n" .
			T1 . "<head>\n" .
				T2 . $this->meta .
				T2 . "<title>" . $this->meta_title . "</title>\n" .
				$this->style .
				$this->script .
			T1 . "</head>\n" .
			T1 . "<body>\n" .
				T2 . "<div id='container'>\n" .
						$this->get_Conteiner() .
				T2 . "</div><!-- container -->\n" .
			T1 . "</body>\n" . 
		"</html>\n" ;
	}

	function get_Conteiner(){
		$c =
		$this->header .
		$this->menu .
		$this->section .
		$this->footer ;
		return $c ;
	}

	function set_Header(){
		$h=
			T3 . "<header>\n" .
				T4 . "<figure id='logo'>\n" .
				T5 .	$this->logo .
				T4 . "</figure>\n" .
				T4 . "<h1>" . $this->body_title . "</h1>\n" .
			T3 . "</header>\n" ;
		$this->header = $h ;
	}

	function set_Menu($menu){
		$menu = 
			T4 . "<nav id='menunav'>\n" .
				$menu . 
			T4 . "</nav>\n" ;
		$this->menu=$menu ;
	}

	function set_Section(){
		$s =
			T3 . "<section>\n" .
				$this->nav .
				$this->article .
				$this->linkcollection .
			T3 . "</section>\n" ;
		$this->section = $s ;
	}

	function set_Nav($navigation){
		$n =
			T4 . "<nav id='liens_navigation' class='top-align'>\n" .
				$navigation .
			T4 . "</nav>\n" ;
		$this->nav = $n ;
	}

	function set_Article($article){
		$a =
			T4 . "<article>\n" .
				$article .
			T4 . "</article>\n" ;
		$this->article = $a ;
	}

	function set_Linkcollection($linkcollection){
		$l =
			T4 . "<aside id='liens_orbitaux' class='top-align'>\n" .
				$linkcollection .
			T4 . "</aside>\n" ;
		$this->linkcollection = $l ;
	}

	function set_Footer(){
		$f =
			T4 . "<footer>\n" .
				$this->get_Footer() .
			T4 . "</footer>\n" ;
		$this->footer = $f ;
	}

	function get_Footer(){
		global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
		return 
			T5 . "<h1>Site de type Godot</h1>\n" .
			T5 . "<p>Le type qu'on attendait.</p>\n" ;
	}
}
?>