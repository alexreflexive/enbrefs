<?php

$config=array();
$config['languages']['en']="English" ;
$config['languages']['fr']="Français" ;
$config['default_lang'] ="en" ;
$config['default_pseudo']['en'] ="Visitor" ;
$config['default_pseudo']['fr'] ="Visiteur" ;
/*
$config['default_user']['id'] = NO_ID ;
$config['default_user']['pseudo'] = $lang[$config['default_lang']][VISITOR] ;
$config['default_user']['email'] = NOTHING ;
$config['default_user']['id_privilege'] = VISITOR ;
$config['default_user']['devise'] = NOTHING ;
$config['default_user']['language'] = $config['default_lang'] ;
*/
$config['css']="<link rel='stylesheet' type='text/css' href='css/styles.css'>" ;
//$config['echo_log'] = true ;

// Ces titres sont à définir par l'utilisateur
$config['meta_title'] = "EnBrefs numériques" ; 
$config['body_title'] = "EnBrefs <p class='right-align'>numériques</p>" ; 

$config['db']['type'] = 'mysql'; // on pourrait avoir 'sqlite', 'postgre'â€¦
$config['db']['host'] = 'xxx.xxx.xxxxxx';
$config['db']['base'] = 'xxxxxx';
$config['db']['user'] = 'xxxxxxxxx';
$config['db']['password'] = 'xxxxxxxxx';
$config['db']['prefix'] = 'enb_'; 
$config['db']['salt'] = 'b4eb5bd81702d0f741231508d2838fd0'; 

/* Paramètres réglés dans config */
// Utile pour éviter un nombre littéral dans les calculs
//$config['pagination']['first_page'] = 1 ;

//Nombre d'éléments qu'on veut afficher par page
// pseudo : ventilation.
//$config['pagination']['rows_per_page'] = 6 ;

// Dans les numéros clicable permettant de naviger dans les pages
// le nombre de numéros avant et après la page courante.
// Si on choisi 2, on aura un bloc de 5
// pseudo : petit_pont ;
//$config['pagination']['nombre_pages_petit_cote'] = 2 ;

//Le même que le précédent, mais en comptant l'élément courant
// $pseudo : grand_pont
//$config['pagination']['nombre_pages_grand_cote'] = 
	//$config['pagination']['nombre_pages_petit_cote'] + 1 ;

// Nombre de numéros du bloc central
// pseudo : plage
//$config['pagination']['page_range'] = 
	//$config['pagination']['nombre_pages_petit_cote'] * 2 + 1 ; 

// Ecart entre le premier élément de la page et le dernier
// pseudo : ecart
//$config['pagination']['page_gap'] = 
	//$config['pagination']['page_range'] - 1 ;

/* Paramètres décidés à l'exécution,
présents ici pour le développement */
// Nombre d'enregistrements divisés par le nombre d'éléments par page, 
// arrondi vers la valeur supérieure.
//$config['pagination']['page_count'] = 50 ;

//Page passée en paramètre
//$config['pagination']['current_page'] = 25 ;
?>
