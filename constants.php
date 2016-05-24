<?php
define('T1', "\t") ;
define('T2', "\t\t") ;
define('T3', "\t\t\t") ;
define('T4', "\t\t\t\t") ;
define('T5', "\t\t\t\t\t") ;
define('T6', "\t\t\t\t\t\t") ;
define('T7', "\t\t\t\t\t\t\t") ;
define('T8', "\t\t\t\t\t\t\t\t") ;
define('T9', "\t\t\t\t\t\t\t\t\t") ;
define('T10', "\t\t\t\t\t\t\t\t\t\t") ;
define('T11', "\t\t\t\t\t\t\t\t\t\t\t") ;
define('T12', "\t\t\t\t\t\t\t\t\t\t\t\t") ;
define('T13', "\t\t\t\t\t\t\t\t\t\t\t\t\t") ;
define('T14', "\t\t\t\t\t\t\t\t\t\t\t\t\t\t") ;

$css_front=
		//T2 . "<link rel='stylesheet' type='text/css' href='css/normalize.css'>\n" .
		T2 . "<link rel='stylesheet' type='text/css' href='css/styles.css'>\n" .
		T2 . "<link rel='stylesheet' type='text/css' href='css/codestyles.css'>\n" ;

$css_back=
		//T3 . "<link rel='stylesheet' type='text/css' href='css/normalize.css'>\n" .
		T4 . "<link rel='stylesheet' type='text/css' href='css/backoffice.css'>\n" .
		T2 . "<link rel='stylesheet' type='text/css' href='css/codestyles.css'>\n" ;


$js=	T2. "<script type='text/javascript' src='js/scripts.js'></script>\n" ;
/*
		T2. "<script type='text/javascript' src='js/tinymce/tinymce.min.js'></script>\n" .
		T2. "<script type='text/javascript'>\n" .
		T3. 	"tinymce.init({\n" .
		T4.			"selector: '.editeur',\n" .
		T4.			"width:550,\n" .
		T4.			"height:300,\n" .
		T4.			"plugins: [\n" .
		T5 .			"'advlist autolink link image lists charmap print preview hr',\n" .
		T5 .			" 'anchor pagebreak spellchecker searchreplace wordcount',\n" .
		T5 . 			" 'visualblocks visualchars code fullscreen insertdatetime',\n" .
		T5 . 			" 'media nonbreaking save table contextmenu directionality',\n" .
		T5 . 			" 'template paste textcolor',\n" .
		T4 . 		"],\n" .
		T4 .		"content_css:'css/backoffice.css'," .
		T4 . 		"toobar: \n" .
		T5 .			"'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor',\n" .
		T4 . 		"style_formats: [\n" .
		T5 .			"{title: 'Header 1', block: 'h1'},\n" .
		T5 .			"{title: 'Header 2', block: 'h2'},\n" .
		T5 .			"{title: 'Header 3', block: 'h3'},\n" .
		T5 .			"{title: 'Header 4', block: 'h4'},\n" .
		T5 .			"{title: 'Header 5', block: 'h5'},\n" .
		T5 .			"{title: 'Header 6', block: 'h6'},\n" .
		T5 .			"{title: 'source_code', block: 'div', classes: 'code_source'}" .
		T4 .		"]\n" .
		T3.		"});\n" .

		T2.	"</script>\n" ;*/


// Images 
define('WIDTH',0);
define('HEIGHT',1);

define('ICON_FOLDER', 'images/icons/dir_logo.png') ;
define('ICON_PARENT_FOLDER', 'images/icons/dir_parent.png') ;
define('IMG_CONTENT', 'content_img') ;
define('EXPLORER_IMG_SIDE', 100) ;


/*
toolbar: "insertfile | | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
*/

/*$meta=	T2 . "<meta Charset='UTF-8'>\n" ;*/
$logo_front=	"<a href='index.php'><img src='images/logo.svg' width='150px'></a>\n" ;
$logo_back=	"<a href='index.php?mode=profile&profile=ownprofile'><img src='images/logo_backoffice.svg'></a>\n" ;
$body_title_front = "EnBrefs <p class='right-align'>numériques</p>" ;
$body_title_back = "" ;
define('CSS_STYLES_FRONTEND', $css_front) ;
define('CSS_STYLES_BACKEND', $css_back) ;
define('JS_SCRIPT', $js) ;
/*define('META', $meta) ;*/
define('LOGO_FRONT', $logo_front) ;
define('LOGO_BACK', $logo_back) ;
define('BODY_TITLE_FRONT', $body_title_front) ;
define('BODY_TITLE_BACK', $body_title_back) ;

define('FRONTEND',1) ;
define('BACKEND',2) ;

define('HOMEPAGE',1) ;
define('PLAN',2) ;
define('ARTICLE_03',3) ;
define('ARTICLE_04',4) ;
define('ARTICLE_05',5) ;
define('ARTICLE_06',6) ;
define('ARTICLE_07',7) ;
define('ARTICLE_08',8) ;
define('ARTICLE_09',9) ;
define('ARTICLE_10',10) ;

define('URL_INDEX', "index.php") ;
define('URL_HOMEPAGE', "index.php?id_article=" . HOMEPAGE) ;
define('URL_PLAN', "index.php?id_article=" . PLAN) ;
define('URL_EXPO_ARTICLE', "index.php?mode=expo&expo=article") ;
define('URL_EXPO_ARTICLE_FWRITER', "index.php?mode=expo&expo=articles_fWriter") ;
define('URL_ARTICLE_SEARCH', "index.php?mode=search") ;

define('URL_BACKEND_OWNPROFILE', "index.php?mode=profile&profile=ownprofile");
//define('URL_BACKEND_RANGE', "index.php?backend=range");
define('URL_BACKEND_MAIN_ADMIN', "index.php?mode=admin&admin=main_form");

define('URL_FORM_LOGIN',"index.php?mode=surfer&surfer=login") ;
define('URL_FORM_REGIN',"index.php?mode=surfer&surfer=register") ;
define('URL_FORM_FORGOT_PASS',"index.php?mode=surfer&surfer=forgotten_pwd") ;
define('URL_LOGOUT',"index.php?mode=logout") ;

define('URL_INBOX', "index.php?mode=messenger&messenger=inbox") ;
define('URL_SENTBOX', "index.php?mode=messenger&messenger=sentbox") ;
define('URL_SENTPMSHOWBOX', "index.php?mode=messenger&messenger=sentpmshowbox") ;
define('URL_INPMSHOWBOX', "index.php?mode=messenger&messenger=inpmshowbox") ;
define('URL_PMEDITBOX', "index.php?mode=messenger&messenger=pmeditbox") ;


define('NO_ID',0) ;
define('NOTHING', "") ; 
define('ALL', "") ;

define('BANNISHED', -1) ;
define('VISITOR', 0) ;
define('MEMBER', 1) ;
define('WRITER', 2) ;
define('CHIEF_EDITOR',3) ;
define('ADMIN', 5) ;
define('ALL_PRIVILEGES', 9) ;

define('DRAFT', 0) ;
define('SUBMITTED', 1) ;
define('APPROVED', 2) ;
define('PUBLISHED', 3) ;

define('SEND_NOT_OPENED', 1) ;
define('SEND_OPENED', 2) ;

define('SENTBOX_MESSENGER', 1) ;
define('INBOX_MESSENGER', 2) ;

// L'inscrit consulte et modifie les formulaires le concernant.
define('AUTOCONSULTANT', 1) ;

// Un administrateur ou un autre ayant-droit consulte ou modifie les formulaires d'autrui.
define('SUPERCONSULTANT', 2) ;


/* Constants to language expressions */
define('ABBREVIATION', 1) ;
define('SAY_IT', 2) ;
define('ADD',1001);
define('ADMIN_corr', 1002) ;
define('ARTICLES_BY',1003) ;
define('DIE_MSG',1043) ;
define('FORGOT_PASS', 1004) ;
define('HOME',1005) ;
define('INBOX', 1006) ;
define('LAST_MODIF',1007) ;
define('LINKS_IN',1008) ;
define('LINKS_OUT',1009) ;
define('LOGIN',1010) ;
define('LOGOUT',1011) ;
define('MEMBERLIST', 1012) ;
define('MESSENGER', 1013) ;
define('MODIFY',1014);
define('MY_ARTICLES',1015) ;
define('MY_PROFILE', 1016) ;
define('MY_RANGE', 1017) ;
define('NAV_ASIDE',1018);
define('NAV_DOWN',1019);
define('NAV_UP',1020);
define('PROFILE',1021);
define('PUBLISHED_BY',1022) ;
define('PUBLISH_DATE',1023) ;
define('RANGE',1024);
define('REGIN', 1025) ;
define('REMOVE',1026) ;
define('SEARCH_RESULTS',1027);
define('SEND_PM_TO',1028);
define('SENTBOX', 1029) ;
define('SHOW_SITE', 1030) ;
define('TITLE_ADMIN',1031);
define('TITLE_ADMINISTRATION', 1032) ;
define('TITLE_ARTICLES',1033);
define('TITLE_EDIT_ARTICLE', 1034) ;
define('TITLE_MEMBERSLIST',1035);
define('TITLE_MEMBERLIST', 1036) ;
define('TITLE_OWNPROFILE',1037);
define('TITLE_OWNRANGE', 1038) ;
define('TITLE_RANGE',1039);
define('TITLE_USERPROFILE',1040);
define('TITLE_USERRANGE',1041);
define ('TITLE_NEW_ARTICLE', 1042) ;

define ('USER_BANNISHED', 1044) ;
define ('USER_VISITOR', 1045) ;
define ('USER_MEMBER', 1046) ;
define ('USER_WRITER', 1047) ;
define ('USER_CHIEF_EDITOR', 1048) ;
define ('USER_ADMIN', 1049) ;

define ('NO_ITEM', 1050) ;

define('PERSONAL_DATA', 1051);
define('SECRET_DATA', 1052);
define('SOCIAL_DATA', 1053);

define('ARTICLE_EDIT', 1054);
define('ARTICLE_MONITORING', 1055);

?>
