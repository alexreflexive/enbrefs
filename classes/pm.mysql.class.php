<?php
class private_message{
	private $db ;
	private $table = "personal_message" ;
	private $intable = "pm_inbox" ;
	private $senttable = "pm_sentbox" ;
	private $identification = "title" ;


	function private_message($access_base, $p){
		$this->db=$access_base ;
		$this->table=$p . $this->table ;
		$this->intable=$p . $this->intable ;
		$this->senttable=$p . $this->senttable ;
	}

	function select_one($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		$query = "SELECT * " .
			"FROM " . $this->table . " " .
			"WHERE id=" . $this->db->quote($id) ;
		return $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
	}

	function select_onesent($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		$query = "SELECT * " .
			"FROM " . $this->senttable . " " .
			"WHERE id=" . $this->db->quote($id) ;
		return $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
	}

	function select_onein($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		$query = "SELECT * " .
			"FROM " . $this->intable . " " .
			"WHERE id=" . $this->db->quote($id) ;
		return $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
	}

	function select_all(){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = "SELECT id, " . $this->identification . " " .
				"FROM " . $this->table ;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$return[] = $r ;
		}
		return $return ;
	}

	// Inbox
	function select_pm_to($id_to, $page, $rows_per_page){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;



		// Rows count
		$query="SELECT COUNT(id) FROM " . $this->intable . " " . "WHERE id_to=" . $id_to ;
		$result = $this->db->query($query) ;
		$pm_count = $result->fetchColumn();
		$page_count = ceil($pm_count  / $rows_per_page) ;
		$limit = ($page-1) * $rows_per_page ;

		$query = "SELECT * "  .
				"FROM " . $this->intable . " " .
				"WHERE id_to=" . $id_to . " " .
				"LIMIT " . $limit . "," . $rows_per_page;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$pm_list['pm'][$r['id']] = $r ;
		}
		$pm_list['pagination']['pm_count'] = $pm_count ;
		$pm_list['pagination']['page'] = $page ;

		$pm_list['pagination']['page_count'] = $page_count ;

		return $pm_list ;
	}

	// Sentbox
	function select_pm_from($id_from, $page, $rows_per_page){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		// Rows count
		$query="SELECT COUNT(id) FROM " . $this->senttable . " " . "WHERE id_to=" . $id_from ;
		$result = $this->db->query($query) ;
		$pm_count = $result->fetchColumn();

		$page_count = ceil($pm_count  / $rows_per_page) ;
		$limit = ($page-1) * $rows_per_page ;

		$query = "SELECT * " .
				"FROM " . $this->senttable . " " .
				"WHERE id_from=" . $id_from . " " .
				"LIMIT " . $limit . "," . $rows_per_page;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$pm_list['pm'][$r['id']] = $r ;
		}
		$pm_list['pagination']['pm_count'] = $pm_count ;
		$pm_list['pagination']['page'] = $page ;

		$pm_list['pagination']['page_count'] = $page_count ;
		return $pm_list ;
	}
/*
		foreach($querys as $query){
			$log = $log . "Query : " . $query  ;
			if($retour = $this->db->exec($query)){
				$log = $log . " -> OK.<br>" ;
			} else {
				if($this->db->errorCode()==PDO::ERR_NONE){
					$log = $log . " -> OK.<br>" ;
				}else{
				$log = $log .  " -> Problème : " . $this->db->errorCode() . " <br>" ;
				}
			}
		}
*/
	function insert_pm($pm){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$fields = " (title, id_from, id_to, status, date, text) " ;
		$values = " (" . 
			$this->db->quote($pm['pm_title']) . ", " . 
			"'" . $pm['from_id'] .  "', " . 
			"'" . $pm['to_id'] .  "', " . 
			"'" . SEND_NOT_OPENED . "', " .
			"NOW(), " .
			$this->db->quote($pm['pm_text']) . ") " ;

		$querys=array() ; $recsucces = array() ;
		$querys['intable'] = "INSERT INTO " . $this->intable  . $fields ." VALUES" . $values ;
		$querys['senttable'] = "INSERT INTO " . $this->senttable  . $fields ." VALUES" . $values ;
/*
		foreach($querys as $key=>$query){
			$log = $log . "Query : " . $query  ;
			if($retour = $this->db->exec($query)){
				$log = $log . " -> OK.<br>" ;
				$recsucces[$key] =$this->db->lastInsertId() ;
			} else {
				if($this->db->errorCode()==PDO::ERR_NONE){
					$log = $log . " -> OK.<br>" ;
					$recsucces[$key] =$this->db->lastInsertId() ;
				}else{
				$log = $log .  " -> Problème : " . $this->db->errorCode() . " <br>" ;
				$recsucces[$key] =false ;
				}
			}
		}
*/

		$log = $log . $querys['intable'] . "<br>" . $querys['senttable'] . "<br>" ;
		//return 1 ;

		if($retour = $this->db->exec($querys['intable'])){
			$log = $log .  "Normalement l'enregistrement s'est bien déroulé.<br>" ;
			$recsucces['intable'] = $this->db->lastInsertId() ;
		} else {
			$log = $log .  "Problème d'enregistrement du message personnel.<br>" .
				$this->db->errorCode() . " <br>" ;
			$recsucces['intable'] =  false ;
		}

		if($retour = $this->db->exec($querys['senttable'])){
			$log = $log .  "Normalement l'enregistrement s'est bien déroulé.<br>" ;
			$recsucces['senttable'] = $this->db->lastInsertId() ;
		} else {
			$log = $log .  "Problème d'enregistrement du message personnel.<br>" .
				$this->db->errorCode() . " <br>" ;
			$recsucces['senttable'] =  false ;
		}	
	return 	$recsucces ;		
	}

	function delete_sentpm($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = 
			"DELETE FROM " . $this->senttable . " " .
			"WHERE id=" . $id ;
		//echo $query . "<br>" ;
		//return true ;
		if($retour = $this->db->exec($query)){
			$log = $log .  "Normalement la suppression s'est bien déroulée.<br>" ;
			return true;
		} else {
			$log = $log .  "Problème de la suppression du message personnel.<br>" .
				$this->db->errorCode() . " <br>" ;
			return false ;
		}		
	}

	function delete_inpm($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = 
			"DELETE FROM " . $this->intable . " " .
			"WHERE id=" . $id ;
		//echo $query . "<br>" ;
		//return true ;
		if($retour = $this->db->exec($query)){
			$log = $log .  "Normalement la suppression s'est bien déroulée.<br>" ;
			return true;
		} else {
			$log = $log .  "Problème de la suppression du message personnel.<br>" .
				$this->db->errorCode() . " <br>" ;
			return false ;
		}		
	}
}

?>