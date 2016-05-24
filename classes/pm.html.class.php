<?php

class messenger{
	function messenger(){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

	}

	function get_Inbox($pm_array, $msg){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
//var_dump($pm_array) ;
//echo $log ;
		global $config;
		require_once('classes/ListGenerator.class.php') ;
		$gl = new ListGenerator ;
		if($msg!=""){$msgbox = "<div class='msgbox'>" .  $msg . "</div>" ;}
		// Erreur quand la boite est vide.
		if(isset($pm_array['pm'])){
			$setofitems['list'] = $this->linkmaker($pm_array['pm'], URL_INPMSHOWBOX) ;
		}
		$setofitems['pagination'] = $pm_array['pagination'] ;
		$setofitems['pagination']['url'] = URL_INBOX ;

		$listdisplay['title'] = "<h1>Ma boite de réception</h1>";
		$listdisplay['article']=$msg . $gl->get_list($setofitems) ;

		return $listdisplay['title']  . $listdisplay['article'];
	}

	function get_Sent($pm_array){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		global $config;
		require_once('classes/ListGenerator.class.php') ;
		$gl = new ListGenerator ;
		if(isset($pm_array['pm'])){
			$setofitems['list'] = $this->linkmaker($pm_array['pm'], URL_SENTPMSHOWBOX) ;
		}
		$setofitems['pagination'] = $pm_array['pagination'] ;
		$setofitems['pagination']['url'] = URL_SENTBOX ;

		$listdisplay['title'] = "<h1>Messages envoyés</h1>";
		$listdisplay['article'] = $gl->get_list($setofitems) ;

		return $listdisplay['title']  . $listdisplay['article'];
	}



	function get_PMShowbox($pm, $user){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		if($pm['id_from']==$user->get_IdMember()){
			$from = $pm['from_pseudo'] ;
			$to = "<a href='index.php?mode=messenger&messenger=pmeditbox&id_recipient=" . $pm['id_to'] . "'>" . $pm['to_pseudo'] . "</a>" ;
		}else{
			$from = "<a href='index.php?mode=messenger&messenger=pmeditbox&id_recipient=" . $pm['id_from'] . "'>" . $pm['from_pseudo'] . "</a>" ;
			$to = $pm['to_pseudo'] ;
		}
		if($pm['pmbox']==INBOX_MESSENGER){
			$pmboxdel="del_inpm" ;
		}elseif($pm['pmbox']==SENTBOX_MESSENGER){
			$pmboxdel="del_sentpm" ;
		}

		$box = 
			"<div class='pm_output'>" .
			"<p class='pm_from'>de " . $from . " pour " . $to . "</p>" .
			"<p>" . $pm['date'] . "</p>" .
			"<hr>" .
			"<h2 class='pm_title'>" . $pm['title'] . "</h2>" .
			"<div class='pm_text'>" . $pm['text'] . "</div>" .
			"<hr>" .
			"<a href='index.php?mode=messenger&messenger=$pmboxdel&id=" . $pm['id'] . "'>Supprimer le message</a>" ;


		$pmdisplay['title'] = "<h1>Message privé</h1>";
		$pmdisplay['article'] = $box ;
		return $pmdisplay['title'] . $pmdisplay['article'] ;

	}

	function get_PMEditbox($new_pm){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
//var_dump($new_pm) ;
		$pm_title = "<h1>Nouveau message privé</h1>";

		$pm_text = 
			"<div class='pm_edit'>" .
				"<form name='pm_form' " . 
					"action='index.php?mode=messenger&messenger=rec_pm' " .
					"method='post'>" .
				"<p class='pm_from'>de " . $new_pm['from_pseudo'] . " pour " . $new_pm['to_pseudo'] . "</p>" .
				"<input type='hidden' name='to_id' value='". $new_pm['to_id'] . "''>" . 
				"<input type='hidden' name='to_pseudo' value='". $new_pm['to_pseudo'] . "''>" . 
				"<input type='text' name='pm_title' class='pm_title' placeholder='Object of message'>" .
				"<textarea name='pm_text'></textarea>" .
				"<div class='porte_SubmitButton'><input type='submit' class='OKButton'></div>\n" .
				"</form>" .
			"</div>" ;
		return $pm_title . $pm_text ;
	}

	private function linkmaker($pm_tab, $root_url){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$pmlinks_array=array();
		foreach($pm_tab as $id_pm => $pm){
			$pmlink =
			"<a href='" . $root_url . "&id_pm=" . $id_pm . "'>" . 
				$pm['title'] . "</a>" ;
			$pmlinks_array[$id_pm] = $pmlink ;
		}
		return $pmlinks_array ;
	}
}

?>