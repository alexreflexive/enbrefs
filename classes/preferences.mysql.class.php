<?php
class SitePreferences{
	private $db ;
	private $table = "site_preferences" ;
	private $identification = "name" ;
	private $raw=array() ;
	private $treated=array() ;
	public $name ;
	public $devise ;
	public $rows_per_page ;
	public $id_inscription ;
	public $echo_log ;

	function SitePreferences($access_base, $p){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$this->db=$access_base ;
		$this->table=$p . $this->table ;
		//$this->read_preferences() ;
	}

	function select_one($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		$query = "SELECT * " .
			"FROM " . $this->table . " " .
			"WHERE id=" . $this->db->quote($id) ;

		$log = $log . "db query : " . $query . "<br>" ;
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
		$log = $log . "db query : " . $query . "<br>" ;
		return $return ;
	}

	function read_preferences(){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$preferences=$this->select_all();
		$this->name= $preferences['name'] ;
		$this->devise= $preferences['devise'];
		$this->default_lang= $preferences['default_lang'];
		$this->rows_per_page= $preferences['rows_per_page'];
		$this->id_inscription= $preferences['id_inscription'];
		$this->echo_log= $preferences['echo_log'];
		}

	function update_preferences($preferences){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$query = 
			"UPDATE " . $this->table . " " .
			"SET " .
				"name=" . $this->db->quote($preferences['name']) . ", " .
				"devise=" . $this->db->quote($preferences['devise']) . ", " .
				"default_lang=" . $this->db->quote($preferences['default_lang']) . ", " .
				"rows_per_page=" . $this->db->quote($preferences['rows_per_page']) . ", " .
				"id_inscription=" . $this->db->quote($preferences['id_inscription']) . ", " .
				"echo_log=" . $this->db->quote($preferences['echo_log']) . " " .
				"WHERE id=" . $this->db->quote($preferences['id']) ;
		$log = $log . "db query : " . $query . "<br>" ;

		if($retour = $this->db->exec($query)){
			return true ;
		} else {
			$log = $log .  "Foirage sur la ligne." .
				$this->db->errorCode() . " <br>" ;
			return false ;
		}
	}
}
?>
