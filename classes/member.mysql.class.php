<?php
class Member{
	private $db ;
	private $table = "members" ;
	private $identification = "pseudo" ;
	public $member_number ;

	function Member($access_base, $p){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$this->db=$access_base ;
		$this->table=$p . $this->table ;

    		$sql = "SELECT COUNT(*) FROM " . $this->table ;
    		$result = $this->db->query($sql);
    		$rows = $result->fetch(PDO::FETCH_ASSOC);
    		$this->member_number=$rows['COUNT(*)'] ;
    		$log = $log . "number of members : " . $this->member_number . "<br>" ;
	}

	function select_one($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		if(is_numeric($id)){
			$query = "SELECT id, pseudo, email, id_privilege, devise, language " .
				"FROM " . $this->table . " " .
				"WHERE id=" . $this->db->quote($id) ;
			$log = $log . $query . "<br>" ;
			$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
			if(!$result){$log = $log . "return false<br>" ;}
			return $result ;
		}else{
			$log = $log . "return false<br>" ;
			return false ;
		}
	}

	function select_all(){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = "SELECT id " . $this->identification . " " .
				"FROM " . $this->table ;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$return[] = $r ;
		}
		return $return ;
	}

	function select_collection($page, $rows_per_page){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		// Rows count
		$query="SELECT COUNT(id) FROM " . $this->table . " " ;
		$log = $log . $query . "<br>" ;
		$result = $this->db->query($query) ;
		$member_count = $result->fetchColumn();
		$page_count = ceil($member_count  / $rows_per_page) ;
		$limit = ($page-1) * $rows_per_page ;

		// Main query
		$query = "SELECT id, pseudo, id_privilege " .
				"FROM " . $this->table . " " .
			"LIMIT " . $limit . "," . $rows_per_page;
		$log = $log . $query . "<br>" ;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$member_collection['members'][$r['id']]['pseudo'] = $r['pseudo'] ;
			$member_collection['members'][$r['id']]['id_privilege'] = $r['id_privilege'] ;
		}
		$member_collection['pagination']['member_count'] = $member_count ;
		$member_collection['pagination']['page'] = $page ;
		$member_collection['pagination']['page_count'] = $page_count ;

		return $member_collection ;
	}


	function insert_values($user, $salt){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$password = hash('sha512', $salt.$user['password']) ;
		$fields = " (pseudo, password, email, id_privilege, devise, language) " ;
		$values = " (" . 
			"'" . $user['pseudo'] . "', " . 
			"'" . $password .  "', " . 
			"'" . $user['email'] .  "', " . 
			"'" . $user['id_privilege'] . "', " .
			"'" . $user['devise'] . "', " .
			"'" . $user['language'] . "') " ;

		$query = "INSERT INTO " . $this->table  . $fields ." VALUES" . $values ;

		if($retour = $this->db->exec($query)){
			return $this->db->lastInsertId() ;
		} else {
			$log = $log .  "Foirage sur la ligne." .
				$this->db->errorCode() . " <br>" ;
			return false ;
		}
	}

	function update_personalprofile($user){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = 
			"UPDATE " . $this->table . " " .
			"SET " .
				"pseudo=" . $this->db->quote($user['pseudo']) . ", " .
				"email=" . $this->db->quote($user['email']) . ", " .
				"id_privilege=" . $this->db->quote($user['id_privilege']) . " " .
			"WHERE id='" . $user['id'] . "' " ;

		if($retour = $this->db->exec($query)){
			return $user ;
		} else {
			$log = $log .  "Foirage sur la ligne." .
				$this->db->errorCode() . " <br>" ;
			return false ;
		}
	}

	function update_secretprofile($user, $salt){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$newpassword = hash('sha512', $salt.$user['new_password']) ;
		$query = 
			"UPDATE " . $this->table . " " .
			"SET " .
				"password=" . $this->db->quote($newpassword) . " " .
			"WHERE id='" . $user['id'] . "' " ;
		//pour le rendre effectif, commenter la ligne 
		//return true et décommenter le bloc suivant.
		//return true ;
		if($retour = $this->db->exec($query)){
			return true ;
		} else {
			$log = $log .  "Foirage sur la ligne." .
				$this->db->errorCode() . " <br>" ;
			return false ;
		}
	}

	function update_socialprofile($user){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = 
			"UPDATE " . $this->table . " " .
			"SET " .
				"devise=" . $this->db->quote($user['devise']) . ", " .
				"language=" . $this->db->quote($user['language']) . " " .
			"WHERE id='" . $user['id'] . "' " ;
		if($retour = $this->db->exec($query)){
			return true ;
		} else {
			$log = $log .  "Foirage sur la ligne." .
				$this->db->errorCode() . " <br>" ;
			return false ;
		}
	}

/*
	function update_values($user, $salt){
		$query = 
			"UPDATE " . $this->table . " " .
			"SET " .
				"pseudo='" . $user['pseudo'] . "' " .
				//"password='" . $user['password'] . "' " .
				"email='" . $user['email'] . "' " .
				"id_privilege='" . $user['id_privilege'] . "' " .
				"devise='" . $user['devise'] . "' " .
				"language='" . $user['language'] . "' " .
			"WHERE id='" . $user['id'] . "' " ;

		echo $query ;
	}
*/

	function test_login($login, $salt){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = 
			"SELECT id, password " .
			"FROM "  . $this->table . "  " .
			"WHERE pseudo='"  . $login['pseudo'] . "'" ;
		$pwdtest = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;

		$testpassword = hash('sha512', $salt.$login['password']);
		if($pwdtest['password']==$testpassword){
			$query = 
				"SELECT id, pseudo, email, id_privilege, devise, language " .
				"FROM "  . $this->table . "  " .
				"WHERE pseudo='"  . $login['pseudo'] . "'" ;
			$response = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
			return $response ;
		}else{
			return false;
		}

	}

	function test_password($id, $password, $salt){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$query = 
			"SELECT password " .
			"FROM " . $this->table . "  " .
			"WHERE id='" . $id . "'" ;
		$password=hash('sha512', $salt.$password);
		if($response = $this->db->query($query)->fetch(PDO::FETCH_ASSOC)){
			if($response['password']==$password){
				return true ;
			}else{
				return false ;
			}
		}
		//var_dump($response)  ;			
	}

	function pseudo_aviable($pseudo){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = "SELECT pseudo FROM "  . $this->table  ;
		if($result = $this->db->query($query)){return true;}
		else {return false;}

	}

}




















//	*****  From TaskList application *****
/*
    function select_one($id){
    	$query = 
    		"SELECT * FROM " . $this->table . " " .
    		"WHERE id=" . $this->db->quote($id) ;
            //echo $query ;
	$result = $this->db->query($query);
	return $result->fetch(PDO::FETCH_ASSOC);
    }

    function select_all(){
    	$query =
   		"SELECT * FROM " . $this->table . " " ;
        $result = $this->db->query($query);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        foreach($result as $r){
            $retour[] = $r ;
        }
        return $retour ;
    }
*/





/*
	function charger($db){
		global $config ;

		$this->table = $config['prefixe_table'] . "eleves " ;
		$query = "SELECT id, nom, prenom, pseudo, niveau " .
			"FROM " . $this->table .
			"WHERE id=" . $db->quote($this->id) ;

			return $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
			*/
?>