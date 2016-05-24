<?php
class ListGenerator{

	function get_list($setofitems){
		global $lang, $lg, $log ; 
		$log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		if(isset($setofitems['list'])){
			$list = $this->get_Items($setofitems['list']) ;
		}else{
			$list=
				"<ul>" . 
					"<li class='odd_line' style='text-align: center'>" . $lang[$lg][NO_ITEM] . "</li>\n" .
				"</ul>" ;
		}
		if(isset($setofitems['extralink'])){
			$extralink = $this->get_ExtraLink($setofitems['extralink']) ;
		}else{
			$extralink="" ;
		}

		if(isset($setofitems['pagination'])){
			$page_suite = $this->get_Pagination($setofitems['pagination']) ;
		}else{
			$page_suite = "" ;
		}

		
		return $extralink . $list . $page_suite ;
	}

	private function get_Items($list){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$parity = "odd_line" ;
		$lines = ""; 

		foreach($list as $clef => $line){
			$lines = $lines . "<li class='" . $parity . "'>" . $line . "</li>\n" ;

			if($parity=="odd_line"){$parity="even_line";} 
			elseif($parity=="even_line"){$parity="odd_line";} 
			$line="";
		}
		$lines = "<ul class='hover_line'>\n" . $lines . "</ul>\n" ;
		return $lines ;
	}

	private function get_ExtraLink($link){

		return "<div class='extra_link'>" . $link . "</div>" ;
	}

	private function get_Pagination($pagination){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$links="" ;
		for($i=1;$i<=$pagination['page_count'];$i++){
			$links = $links . "<a href='" . $pagination['url']. "&page=" . $i . "'> " . $i . " </a>"  ;
		}

		return "<div class='nav_pagination'> ". $links . "</div>" ;
	}
}

?>