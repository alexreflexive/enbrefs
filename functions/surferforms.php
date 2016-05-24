<?php

function LoginForm(){
	global $log, $lang, $lg ;
	$log = $log .   __METHOD__  . "<br>" ;

	$lf =
		"<h1>" . $lang[$lg][LOGIN] . "</h1>
		<div id='mainzone'>
			<div class='formulaire'>
				<form name='login' action='index.php?mode=surfer&surfer=testlogin' method='post' onsubmit='return validate_form()'>
					<input type='hidden' name='form' value='testlogin'>
					<input type='input' name='pseudo' placeholder='pseudo' class='input_texts required'>
					<input type='password' name='password' placeholder ='******' class='input_texts required'>
					<input type='submit'>
				</form>
			</div>
		</div>" ;
	return $lf ;
}

function ReginForm(){
	global $log, $lang, $lg ;
	$log = $log .   __METHOD__  . "<br>" ;

	$reg =
		"<h1>" . $lang[$lg][REGIN] . "</h1>
		<div id='mainzone'>
			<div class='formulaire'>
				<form name='register' action='index.php?mode=surfer&surfer=tryregin' method='post' onsubmit='return validate_form()'>
					<input type='hidden' name='form' value='tryregin'>
					<input type='input' name='pseudo' placeholder='pseudo' class='input_texts required'>
					<input type='email' name='email' placeholder='email' class='input_texts required'>
					<input type='password' name='password' placeholder ='******' class='input_texts required'>
					<input type='submit'>
				</form>
			</div>
		</div>" ;
	return $reg ;
}

function MessageOutPut($msg_number){
	switch($msg_number){

		case 1:
		$msg = "Vous avez bien été enregistré." ;
		break ;

		case 2 :
		$msg = "Un problème est survenu au cours de l'enregistrement." ;
		break ;
	}
	return "<div class='formulaire'>" . $msg . "</div>" ;



}

function ForgottenPwdForm(){
	global $log, $lang, $lg ;
	$log = $log .   __METHOD__  . "<br>" ;

	$fmo=
		"<h1>" . $lang[$lg][FORGOT_PASS] ."</h1>
		<div id='mainzone'>
			<div class='formulaire'>
				<form name='forgot_pass' action='index.php?mode=surfer&surfer=forgotten' method='post' onsubmit='return validate_form()'>
					<input type='email' name='email' placeholder='email' class='input_texts required'>
					<input type='hidden' name='form' value='process_forgot_pass'>
					<input type='submit'>
				</form>
			</div>
		</div>" ;
	return $fmo ;
}

/*function get_MenuForm($formulaire){
	global $log, $lang, $lg ;
	$log = $log .   __METHOD__  . "<br>" ;
	
	$visitor =
		"<li><a href='index.php?mode=expo&expo=article' class='bottom-align'>" . $lang[$lg][HOME] . "</a></li>\n" ;
	
	$login = 
		"<li><a href='index.php?mode=form&form=login' class='bottom-align'>" . $lang[$lg][LOGIN] . "</a></li>\n" ;

	$register = 
		"<li><a href='index.php?mode=form&form=register' class='bottom-align'>" . $lang[$lg][REGISTER] . "</a></li>\n" ; 
		//echo "constante register " . REGISTER . " " . $lang[$lg][REGISTER] . "<br>" ;
		//echo "constante déconnexion " . LOGOUT . " " . $lang[$lg][LOGOUT] . "<br>" ;
		//echo "variable register " . $register . "<br>" ;
	$forgottent_pwd = 
		"<li><a href='index.php?mode=form&form=forgotten_pwd' class='bottom-align'>" . $lang[$lg][FORGOT_PASS] . "</a></li>\n" ;

	$LoginMenu = 	"<ul id='menu' class='bottom-align'>\n" . $visitor . $register . $forgottent_pwd . "</ul>" ;
	$RegisterMenu = 	"<ul id='menu' class='bottom-align'>\n" . $visitor . $login . $forgottent_pwd . "</ul>" ;
	$ForgottentPassMenu = 	"<ul id='menu' class='bottom-align'>\n" . $visitor . $register . $login . "</ul>" ;

	switch($formulaire){
		case 'login':
		$menu = $LoginMenu ;
		break;

		case 'register':
		$menu =$RegisterMenu ;
		break;

		case 'forgotten_pwd':
		$menu = $ForgottentPassMenu ;
		break;

		default:
		$menu="get_MenuForm : Une erreur a dû se produire.<br>" ;
		break;
		}
		return $menu ;
}*/









?>
