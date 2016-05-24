<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// Atelier/At-03_bbEditeur/Admin/Editeur_class.php/////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<script language="JavaScript" type="text/javascript">
// Helpline messages
defaut_help = "Informations sur les boutons";
b_help = "Texte en gras";
i_help = "Texte en italique";
u_help = "Texte souligné";
l_help = "Aligner à gauche";
c_help = "Aligner au centre";
r_help = "Aligner à droite";
j_help = "Justifier";
q_help = "Citation";
o_help = "Liste ordonnée";
img_help = "Image : [img]url_image[/img]";
url_help = "Lien : [url]adresse_url[/url] ou [url=adresse_url]Etiquette[/url]";
couleur_help = "Couleur du texte";
style_help = "Style du texte";
header_help = "Style d'en-tête";
taille_help = "Taille de la police";

function helpline(help) 
	{
	document.editeur.informations.value = eval(help + "_help");
	}


function insertion(repdeb, repfin) 
	{  
	var input = document.forms['editeur'].elements['text'];  
	input.focus();  
	/* pour l'Explorer Internet */  
	if(typeof document.selection != 'undefined') 
		{    
		/* Insertion du code de formatage */    
		var range = document.selection.createRange();    
		var insText = range.text;    range.text = repdeb + insText + repfin;    
		/* Ajustement de la position du curseur */    
		range = document.selection.createRange();    
		if (insText.length == 0) 
			{      
			range.move('character', -repfin.length);    
			} 
		else 
			{      
			range.moveStart('character', repdeb.length + insText.length + repfin.length);
			}    
		range.select();  }  
		/* pour navigateurs plus récents basés sur Gecko*/  
		else if(typeof input.selectionStart != 'undefined')  
			{    
			/* Insertion du code de formatage */    
			var start = input.selectionStart;    
			var end = input.selectionEnd;    
			var insText = input.value.substring(start, end);    
			input.value = input.value.substr(0, start) + repdeb + insText + repfin + input.value.substr(end);
			/* Ajustement de la position du curseur */    
			var pos;    
			if(insText.length == 0) 
				{      
				pos = start + repdeb.length;    
				} 
			else 
				{      
				pos = start + repdeb.length + insText.length + repfin.length;    
				}    
			input.selectionStart = pos;    
			input.selectionEnd = pos;  
			}  /* pour les autres navigateurs */  
		else  
			{    
			/* requête de la position d'insertion */    
			var pos;    
			var re = new RegExp('^[0-9]{0,3}$');    
			while(!re.test(pos)) 
				{      
				pos = prompt("Insertion à la position (0.." + input.value.length + "):", "0");
				}    
			if(pos > input.value.length) 
				{      
				pos = input.value.length;    
				}    
				/* Insertion du code de formatage */    
				var insText = prompt("Veuillez entrer le texte à formater:");    
				input.value = 
					input.value.substr(0, pos) + repdeb + insText + repfin + input.value.substr(pos);
			}
	}
		
</script>

<?php
	
class Editeur
	{
	private $TT ;
	private $interface ;
	private $execution = "editer" ;
	
	//private $ZonePrevisualisation ;
	
	private $HiddenValues ;
	private $infos ;


/* ********************************************  METHODES GENERALES ************************************* */

	function Editeur($article)
		{
		//var_dump($article) ;
		if(IsSet($article['preview'])) 
			{
			//$article = $pilote['article'] ;
			if($article['id'] == 0) $titre = "Nouvel article" ;
			else $titre = "Edition de " . $article['title'] ;
			$ZonePrevisualisation = $this->set_ZonePrevisualisation($article['text']) ;
			}
		else $ZonePrevisualisation = "" ;
		$ZoneID = $this->set_ZoneIDEdition($article['id']) . $this->set_ZoneDateEdition($article['published']) ;
		$ZoneNom = $this->set_ZoneNomEdition($article['name']) ;
		$ZoneTitre = $this->set_ZoneTitreEdition($article['title']) ;
		//$ZoneRubriques = $pilote['options_rubriques'] ;
		$ZoneAuteurArticle = $this->set_ZoneAuteurEdition($article['id_writer']) ;
		$ZoneStatutArticle = $this->set_ZoneStatutEdition($article['id_status']) ;
		$ZoneChapeau = $this->set_ZoneChapeauEdition($article['abstract']) ;
		$BarreOutilsHaut = $this->set_BarreOutilsEdition_1() ;
		$BarreOutilsBas = $this->set_BarreOutilsEdition_2() ;
		$ZoneTexte = $this->set_ZoneTexteEdition($article['text']) ;
		$ZoneInfos = $this->set_Infos() ;
		if(IsSet($this->patch)) {$ZonePatch = $this->patch   ;}else{$ZonePatch="";}
		$ZoneValidation = $this->set_Validation($article['id']) ;
		
		//$action_url = "index.php?feuille=edition&id_rubrique=" . $article['id_rubrique'] . "&id_article=" . $article['id_article'] ;
		$action_url = "index.php?mode=article&id_article=" . $article['id'] ;
		$Hidden = $this->set_HiddenEdition($article['id'], $article['id_parent'], $article['published'], $article['modified']) ;
		
		$formulaire = 
			$ZonePrevisualisation .
			"<form name=editeur action='" . $action_url . "' method=post>\n" .
			"<table id=formulaire width=400px>\n" .
				"<tr><td>" . $ZoneID . "</td></tr>" .
				"<tr><td style='width:100%'>" . $ZoneNom . "</td></tr>\n" .
				"<tr><td style='width:100%'>" . $ZoneTitre . "</td></tr>\n" .
				//"<tr><td style='width:100%'>" . $ZoneRubriques . "</td></tr>\n" .
				"<tr><td style='width:100%'>" . $ZoneAuteurArticle . "</td></tr>\n" .
				"<tr><td style='width:100%'>" . $ZoneStatutArticle . "</td></tr>\n" .
				"<tr><td style='width:100%'>" . $ZoneChapeau . "</td></tr>\n" .
				"<tr><td style='width:100%'>" . 
					"<table width=100%>" .
						"<tr><td>" . $BarreOutilsHaut . "</td></tr>" .
						"<tr><td>" . $BarreOutilsBas . "</td></tr>" .
						"<tr><td>" . $ZoneTexte . "</td></tr>" .
						"<tr><td>" . $ZoneInfos . "</td></tr>" .
					"</table>" .
				"</td></tr>\n" .
				"<tr><td style='text-align:center;'>" . $ZoneValidation . "</td></tr>\n" .
				"<tr><td style='width:100%'>" . $ZonePatch . "</td></tr>\n" .
			"</table>\n" .
			$Hidden . "\n" ;
			"</form>\n" ;
			
		$this->interface = $formulaire ;
		}

	function get_Interface(){return $this->interface; }

/* ********************************************  METHODES D'EDITION ************************************* */

	function set_ZonePrevisualisation($texte)
		{
		return 
		"<div  id='preview'>" .
		prepare_pour_afficher($texte) .
		"</div>" ;
		}

	function set_BarreOutilsEdition_1()
		{
		$combo_headers = 
			"<select name='headerclasses' style='width:100px;' 
			onChange=\"insertion('['+this.value+']' , '[/'+this.value+']')\" 
			onMouseOver=helpline('header') 
			onMouseOut=helpline('defaut')>" .
				"<option value='' selected>Pas de en-tête</option>" .
				"<option value='h1'>h1</option>" .
				"<option value='h2'>h2</option>" .
				"<option value='h3'>h3</option>" .
				"<option value='h4'>h4</option>" .
				"<option value='h5'>h5</option>" .
				"<option value='h6'>h6</option>" .
			"</select>" ;

		$combo_styles = 
			"<select name='styleclasses' style='width:100px;' 
			onChange=\"insertion('[style='+this.value+']' , '[/style]')\" 
			onMouseOver=helpline('style') 
			onMouseOut=helpline('defaut')>" .
				"<option value='' selected>Pas de style</option>" .
				"<option value='terminal'>Terminal</option>" .
				"<option value='textfile'>Textfile</option>" .
				"<option value='exemple'>Exemple</option>" .
				"<option value='note'>Note</option>" .
			"</select>" ;

		$bouton_gras = 
			"<input type='button' class='out' value = G ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[b]', '[/b]')\" 
			onMouseOver=\"this.className='over'; helpline('b')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";> " ;

		$bouton_italique = 
			"<input type='button' class='out' value = I ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[i]', '[/i]')\" 
			onMouseOver=\"this.className='over'; helpline('i')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; 
			helpline('defaut')\";>" ;

		$bouton_souligne = 
			"<input type='button' class='out' value = S ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[u]', '[/u]')\" 
			onMouseOver=\"this.className='over'; helpline('u')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\">" ;
	
		$bouton_gauche = 
			"<input type='button' class='out' value = Gauche ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[left]', '[/left]')\" 
			onMouseOver=\"this.className='over'; helpline('l')\" ; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";>" ;
	 
		$bouton_centre = 
			"<input type='button' class='out' value = Centre ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[center]', '[/center]')\" 
			onMouseOver=\"this.className='over'; helpline('c')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";>" ;
			
		$bouton_droite = 
			"<input type='button' class='out' value = Droite ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[right]', '[/right]')\" 
			onMouseOver=\"this.className='over'; helpline('r')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";>" ;

		$bouton_justifie = 
			"<input type='button' class='out' . value = Justifié ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[justify]', '[/justify]')\" 
			onMouseOver=\"this.className='over'; helpline('j')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";>" ;

		$boutons_1 = 	
			$combo_headers .
			$combo_styles .
			$bouton_gras . 
			$bouton_italique . 
			$bouton_souligne . 
			$bouton_gauche . 
			$bouton_centre . 
			$bouton_droite . 
			$bouton_justifie ;
		
		return $boutons_1  ;
		}

	function set_BarreOutilsEdition_2()
		{
		$combo_couleurs = 
			"<select name='couleurs' style='width:100px;' 
			onChange=\"insertion('[color='+this.value+']' , '[/color]')\" 
			onMouseOver=helpline('couleur') 
			onMouseOut=helpline('defaut')>" .
				"<option style='color:black; background-color: beige' value='black'>Noir</option>" .
				"<option style='color:darkred; background-color:beige' value='darkred' >" .
					"Rouge foncé" .
				"</option>" .
				"<option style='color:red; background-color:beige' value='red'>Rouge</option>" .
				"<option style='color:orange; background-color:beige' value='orange'>Orange</option>" .
				"<option style='color:brown; background-color:beige' value='brown'>Marron</option>" .
				"<option style='color:yellow; background-color:beige' value='yellow'>Jaune</option>" .
				"<option style='color:green; background-color:beige' value='green'>Vert</option>" .
				"<option style='color:olive; background-color:beige' value='olive'>Olive</option>" .
				"<option style='color:cyan; background-color:beige' value='cyan'>Cyan</option>" .
				"<option style='color:blue; background-color:beige' value='blue'>Bleu</option>" .
				"<option style='color:darkblue; background-color:beige' value='darkblue'>" .
					"Bleu foncé" .
				"</option>" .
				"<option style='color:indigo; background-color:beige' value='indigo'>Indigo</option>" .
				"<option style='color:violet; background-color:beige' value='violet'>Mauve</option>" .
				"<option style='color:white; background-color:beige' value='white'>Blanc</option>" .
			"</select>" ;

		$zone_taille = "Taille : 
			<input type='text' name='taille' size=2 value=1.0 
			style='width:100px;'
			onMouseOver=helpline('taille') 
			onMouseOut=helpline('defaut')>" .
			"<input type='button' value='Appliquer' 
			onMouseOver=helpline('taille') 
			onMouseOut=helpline('defaut') 
			onClick=\"insertion('[taille='+taille.value+']','[/taille]')\">" ;
		
		$bouton_url = 
			"<input type='button' class='out' value = URL ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[url]', '[/url]')\" 
			onMouseOver=\"this.className='over'; helpline('url')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";>" ;

		$bouton_image = 
			"<input type='button' class='out' value = Image ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[img width=100px]', '[/img]')\" 
			onMouseOver=\"this.className='over'; helpline('img')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";>" ;
/*
		$bouton_url = 
			"<input type='button' class='out' value = URL ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[img]', '[/img]')\" 
			onMouseOver=\"this.className='over'; helpline('img')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";>" ;

		$bouton_image = 
			"<input type='button' class='out' value = Image ;  
			onMouseDown=this.className='click' ; 
			onClick=\"insertion('[url]', '[/url]')\" 
			onMouseOver=\"this.className='over'; helpline('url')\"; 
			onMouseUp=this.className='over'; 
			onMouseOut=\"this.className='out'; helpline('defaut')\";>" ;
*/
		$boutons_2 = 	$combo_couleurs . 
					$zone_taille . 
					$bouton_url . 
					$bouton_image ;
		
		return $boutons_2  ;
		}

	function set_ZoneIDEdition($id_article)
		{
		return "<textarea disabled=true  name='id' id='ZoneTitre' style='height:20px;width:20px;text-align:center'>" . 
				$id_article . 
			"</textarea>" ;
		}

	function set_ZoneDateEdition($date)
		{
		return "<input type=text name=published disabled=true style='width:270px;margin-left:5px' value='" . $date . "'>" ;
		}

	function set_ZoneNomEdition($nom)
		{
		return "<textarea name='name' id='ZoneTitre' style='height:20px;width:320px;'>" . 
				$nom . 
			"</textarea>" ;
		}

	function set_ZoneTitreEdition($titre)
		{
		return "<textarea name='title' id='ZoneTitre' style='height:20px;width:450px;'>" . 
				$titre . 
			"</textarea>" ;
		}
/*
	function set_ZoneRubriques($rubriques, $selection)
		{
		foreach ($rubriques as $id_rubrique => $nom_rubrique)
			{
			if($selection == $id_rubrique) $attribut = "selected" ; else $attribut = "" ;
			$options = $options . 
				"<option value=" . $id_rubrique . " " . $attribut . ">" . 
					$nom_rubrique .
				"</option>" ;
			}
		return "<select name=id_rubrique>" . $options . "</select>" ;
		}
*/
	function set_ZoneAuteurEdition($auteur)
		{
		return "<textarea name='id_writer' id='ZoneTitre' style='height:20px;width:450px;'>" . 
				$auteur . 
			"</textarea>" ;
		}

	function set_ZoneStatutEdition($sel_id_statut)
		{
			/*
		$radio="";
		foreach(get_Status(ALL) as $id_statut => $nom_statut)
			{
			if($id_statut == $sel_id_statut) $check = "checked" ;
			else $check = "" ;
			$radio = $radio . 
				"<li><input type=radio name='statut_article' value='" . $id_statut . "' " . $check . ">" . get_Status($id_statut) . "</li>" ;
			}
			$radio = "<ul style='list-style-type:none'>" . $radio . "<Ul>" ;
			return $radio ;
			*/
			$combobox = "" ;
			$tab_status = get_Status(ALL) ;
			foreach($tab_status as $id => $status){
				if($id == $sel_id_statut ){//$article['id_status']){
					$attribute=" selected" ;
				}else{
					$attribute="" ;
				}
				$combobox = $combobox . 
					"<option value='" . $id . "'" . $attribute . ">" . $status['label'] . "</option>\n" ;
			}
			$str_statut = "<select name='id_status'>" . $combobox . "</select>" ;
			return $str_statut ;

		}

	function set_ZoneChapeauEdition($chapeau)
		{
		return "<textarea name='abstract' id='ZoneResume' style='height:75px;width:450px;'>" . 
			$chapeau . 
		"</textarea>" ;
		}
		
	function set_ZoneTexteEdition($texte)
		{
		return "<textarea name='text' id='ZoneTexte' style='height:500px;width:450px;'>" . 
			$texte . 
		"</textarea>" ;
		}

	function set_Patch($supplements)
		{
		$this->patch = $supplements ;
		}

	function set_Infos()
		{		
		return "<input type=text 
					class='informations' 
					name='informations' 
					value='Informations sur les boutons' 
					style='width:100%'>" ;
		}
		
	function set_Validation($id)
		{
		return
			"<input type=submit 
				formaction='index.php?mode=article&article=rec_content&id_article=$id' 
				name='enregistrer' 
				value='Enregistrer' 
				style='width:100px;margin:0 5px 0 5px ;'>" .  
			"<input type=submit 
				formaction='index.php?mode=article&article=preview&id_article=$id' 
				name='previsualiser' 
				value='Previsualiser' 
				style='width:100px;margin:0 5px 0 5px ;'>" ;
		}
		
	function set_HiddenEdition($id, $id_parent, $published, $modified)
		{
		$retour = 
			"<input type=hidden name=id_article value='" . $id . "'>" .
			"<input type=hidden name=id_parent value='" . $id_parent . "'>" .
			"<input type=hidden name=published value='" . $published . "'>" .
			"<input type=hidden name=modified value='" . $modified . "'>" ;
		return $retour ;
		}
	}
?>














