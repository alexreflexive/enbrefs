<?php
session_start();
//var_dump($_SESSION) ; 
//exit() ;
//session_destroy() ;
$log = $_SERVER['HTTP_USER_AGENT'] . "<br>";
require_once('includes.php') ;
$mabd = new MySQL($config['db']) ;
$echo_log=$mabd->preference['echo_log'] ;
$lg=$mabd->preference['default_lang'] ; 
$language_file = "Langages/lang_" . $lg . ".php" ;
require_once($language_file);
$language = $lang[$lg] ;
$pilote =  http_extract($_REQUEST, $_SESSION, $mabd) ;
$log = $pilote['log'] ;
//$css = $config['css'] . $pilote['css'] ;
$css = $pilote['css'] ;
$html = new webpage ($config['meta_title'], $css, JS_SCRIPT , $pilote['body_title'], $pilote['logo']) ;
$html->set_Header() ;
$html->set_Menu($pilote['menu']) ;
$html->set_Nav($pilote['navigation']) ;
$html->set_Article($pilote['article']) ;
$html->set_LinkCollection($pilote['external_links']) ;
$html->set_Footer() ;
$html->show();

$log = $log . "<a href='index.php'>Home</a> | 
		<a href='" . URL_LOGOUT . "'>Logout</a> |  
		<a href='tests/image_folder.php'>Tests</a>"   ;
if(!empty($_SESSION)){
	if($echo_log && $_SESSION['id_privilege'] >= ADMIN){
		echo $log . "<br><br><br><br><br>" ;
	}
}
?>