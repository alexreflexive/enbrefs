<?php




function prepare_pour_afficher($message)
	{
	return  preg_replace(bbcode(),htmlcode(),htmlspecialchars($message)) ;
	//return  nl2br($message) ;
	}

function bbcode()
	{
	$bbcode= array 
		(
		"/\n/",
		"/\[taille=(.+?)\](.+?)\[\/taille\]/si", 	//1
		"/\[color=(.+?)\](.+?)\[\/color\]/si",		//2
		"/\[b\](.+?)\[\/b\]/si",					//3
		"/\[i\](.+?)\[\/i\]/si",						//4
		"/\[u\](.+?)\[\/u\]/si",					//5
		"/\[left\](.+?)\[\/left\]/si",				//6
		"/\[center\](.+?)\[\/center\]/si",			//7
		"/\[right\](.+?)\[\/right\]/si",			//8
		"/\[justify\](.+?)\[\/justify\]/si",			//9
		"/\[note\](.+?)\[\/note\]/si",				//10
		"/\[code\](.+?)\[\/code\]/si",			//11
		"/\[img\](.+?)\[\/img\]/si",				//12
		"/\[img width=([0-9]+)px\](.+?)\[\/img\]/si",	//13
		"/\[img width=([0-9]+)%\](.+?)\[\/img\]/si",	//14
		"/\[img height=([0-9]+)px\](.+?)\[\/img\]/si",	//15
		"/\[url=(.+?)\](.+?)\[\/url\]/si",			//16
		"/\[url\](.+?)\[\/url\]/si",					//17
		"/\[h([0-6])\](.+?)\[\/h([0-6])\]/si",		//18
		"/\[style=(.+?)\](.+?)\[\/style\]/si",		//19
		"/\[table style=(.+?)\](.+?)\[\/table\]/si",					//20
		"/\[table border=([0-9]+)\](.+?)\[\/table\]/si",					//20
		"/\[table border=([0-9]+) style=(.+?)\](.+?)\[\/table\]/si",					//20
		"/\[table style=(.+?) border=([0-9]+)\](.+?)\[\/table\]/si",					//20
		"/\[table\](.+?)\[\/table\]/si",					//21
		"/\[tr style=(.+?)\](.+?)\[\/tr\]/si",					//22
		"/\[tr\](.+?)\[\/tr\]/si",					//23
		"/\[td style=(.+?)\](.+?)\[\/td\]/si",					//24
		"/\[td\](.+?)\[\/td\]/si"					//25
		);
		
	return $bbcode ;
	}
	
function htmlcode()
	{
	$htmlcode= array 
		(
		"<br>",
		"<span style='font-size:$1em'>$2</span>", //l'anti-slash ne devrait pas être avant l'apostrophe?
		"<span style='color:$1'>$2</span>",		//2
		"<b>$1</b>",					//3
		"<i>$1</i>",					//4
		"<u>$1</u>",					//5
		"<div style='text-align:left'>$1</div>",	//6
		"<div style='text-align:center'>$1</div>",	//7
		"<div style='text-align:right'>$1</div>",	//8
		"<div style='text-align:justify'>$1</div>",	//9
		"<div style='width:50%;float:right'>$1</div>",	//10
		"<div style='background-color:#FFCCCC;border:thick;font-family:\"Lucida Console\", \"Courier New\";'>$1</div>",
		"<img src='$1' border='0'>",			//12
		"<img src='$2' width='$1px' border='0'>",	//13
		"<img src='$2' width='$1%' border='0'>",	//14
		"<img src='$2' height='$1px' border='0'>",	//15
		"<a href=$1 target=_blank>$2</a>",		//16
		"<a href=$1 target=_blank>$1</a>",		//17
		"<h$1>$2</h$3>",							//18
		//"<pre><div class=$1>$2</div></pre>"					//19
		"<div class=$1>$2</div>",					//19
		"<table style='$1'>$2</table>",					//20
		"<table border='$1'>$2</table>",					//20
		"<table border='$1' style='$2'>$3</table>",					//20
		"<table style='$1' border='$2'>$3</table>",					//20
		"<table>$1</table>",					//21
		"<tr style='$1'>$2</tr>",					//22
		"<tr>$1</tr>",					//23
		"<td style='$1'>$2</td>",					//24
		"<td>$1</td>"					//25
		);
		
	return $htmlcode ;
	}

function get_expo_list($tab_articles, $pagination, $title){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	require_once('ListGenerator.class.php') ;

	$h1 = "<h1>" . $title . "</h1>\n" ;

	foreach ($tab_articles as $id => $article) {
			$list[$id] = 
				"<a href='" . "index.php?id_article=" . $id . "'>" . 
					$article['title'] . " (" . $article['nom'] . ")</a>" ;
		}	
	$gl = new listgen ;
	$str_List = $h1 . $gl->get_list($list, $pagination, "index.php?") ;
	return $str_List ;
}

function get_Privileges($id){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	$privilege[BANNISHED] = "Banni" ;
	$privilege[VISITOR] = "Visiteur" ;
	$privilege[MEMBER] = "Inscrit" ;
	$privilege[WRITER] = "Rédacteur" ;
	$privilege[CHIEF_EDITOR] = "Rédacteur en chef" ;
	$privilege[ADMIN] = "Administrateur" ;
	if($id==ALL_PRIVILEGES){
		return $privilege;
	}else{
		return $privilege[$id] ;
	}
}

function get_Status($id){
	global $log  ; $log = $log . "<b>" . __METHOD__  . "</b><br>" ;
	$status[DRAFT]['label'] = "Brouillon" ;
	$status[SUBMITTED]['label'] = "Soumis" ;
	$status[APPROVED]['label'] = "Approuvé" ;
	$status[PUBLISHED]['label'] = "Publié" ;
	if($id==ALL){
		return $status;
	}else{
		return $status[$id] ;
	}
}
/* déplacé dans functions/backendforms.php
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
*/
function get_RegisterModes($id){
	$rm=array(
		1=>"Inscription directe",
		2=>"Inscription au mail de contrôle" ,
		3=>"Inscription manuelle");
	if($id==0){
		return $rm ;
	}else{ return $rm[$id] ;}
}


?>
