<?php
class Article{
	private $db ;
	private $table = "articles" ;
	private $identification = "name" ;
	private $raw=array() ;	// résultat de scan 1, articles par ordre d'enregistrement.
	private $treated=array() ;	// résultat de scan 2, articles suivant les pointeurs.
	public $article_number ;


	function Article($access_base, $p){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$this->db=$access_base ;
		$this->table=$p . $this->table ;

    		$sql = "SELECT COUNT(*) FROM " . $this->table ;
    		$result = $this->db->query($sql);
    		$rows = $result->fetch(PDO::FETCH_ASSOC);
    		$this->article_number=$rows['COUNT(*)'] ;
    		$log = $log . "number of articles : " . $this->article_number . "<br>" ;
    		if($this->article_number==0){$this->insertFirstRows();}
	}

//////////////////////////// ARTICLE READING /////////////////////////////////////

	function select_one($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		$query = "SELECT * " .
			"FROM " . $this->table . " " .
			"WHERE id=" . $this->db->quote($id) ;
		$log = $log . $query . "<br>" ;
		$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
		if(!$result){$log = $log . "return false<br>" ;}
		return $result ;
	}

	function select_all(){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = "SELECT id, " . $this->identification . ", id_parent, id_previous, id_next, id_first, id_last " .
				"FROM " . $this->table ;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$return[] = $r ;
		}
		return $return ;
	}

	function select_one_name($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		$query = "SELECT name " .
			"FROM " . $this->table . " " .
			"WHERE id=" . $this->db->quote($id) ;
		$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
		return $result['name'] ;
	}


	function select_articles_fWriter($id_writer, $page, $rows_per_page){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		// Rows count
		$query="SELECT COUNT(id) FROM " . $this->table . " " . "WHERE id_writer=" . $id_writer ;
		$result = $this->db->query($query) ;
		$article_count = $result->fetchColumn();
		$page_count = ceil($article_count  / $rows_per_page) ;
		$limit = ($page-1) * $rows_per_page ;
		
		// Main query
		$query = "SELECT id, title " .
			"FROM " . $this->table . " " .
			"WHERE id_writer=" . $id_writer . " " .
			"LIMIT " . $limit . "," . $rows_per_page;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$article_list['articles'][$r['id']] = $r['title'] ;
		}
		$article_list['pagination']['article_count'] = $article_count ;
		$article_list['pagination']['page'] = $page ;
		$article_list['pagination']['page_count'] = $page_count ;

		$article_list['id_writer'] = $id_writer ;


		return $article_list ;
	}

	function select_articles_search($page, $rows_per_page){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		
		// Rows count
		$query="SELECT COUNT(id) FROM " . $this->table ;
		$result = $this->db->query($query) ;
		$article_count = $result->fetchColumn(); 
		$page_count = ceil($article_count  / $rows_per_page) ;
		$limit = ($page-1) * $rows_per_page ;
		
		// Main query
		$query = "SELECT id, title " .
			"FROM " . $this->table . " " .
			"LIMIT " . $limit . "," . $rows_per_page;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$article_list['articles'][$r['id']] = $r['title'] ;
		}
		$article_list['pagination']['article_count'] = $article_count ;
		$article_list['pagination']['page'] = $page ;
		$article_list['pagination']['page_count'] = $page_count ;

		return $article_list ;
	}



//////////////////////////////////// ARTICLE SCANNING /////////////////////////////////////////


	function scan_articles(){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$raw_scan =  $this->scan_part_1() ;
		$id_start = 1 ; $id_end=2 ; 
		$loop_count = 1 ;
		if($this->article_number!=0){
			$log = $log . "First article<br>" ;
			$this->treated[$id_start] = $this->raw[$id_start];
			$level=1 ;
			$this->treated[$id_start]['level'] = $level;
			$pointer=$id_start ;
			$family_count = 1 ;
			$this->scan_part_2($pointer, $level, true, $id_end, $loop_count, $family_count) ;
			return $this->treated ;
		}else{return false;}
	}

	private function scan_part_1(){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = "SELECT id, name, id_parent, id_previous, id_next, id_first, id_last " .
				"FROM " . $this->table ;
		$result = $this->db->query($query) ;
		$result->setFetchMode(PDO::FETCH_ASSOC) ;
		foreach($result as $r){
			$raw[$r['id']] = $r ;
		}
		$this->raw = $raw ;
	}

	private function scan_part_2($pointer, $level, $deeper, $id_end, $loop_count, $family_count){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$log = $log . "Loop count : " . $loop_count . " - Family count : " . $family_count . "<br>" ;
		$loop_count++ ; 
		if($deeper){ // On va en profondeur
			$log = $log . "Deeper true. Pointer : " . $this->raw[$pointer]['id'] . ". " . $this->raw[$pointer]['name'] . "<br>" ;
			$id_first = $this->raw[$pointer]['id_first'] ;
			if($id_first != 0){
				$log = $log . "There is a child : " . $id_first . ". " . $this->raw[$id_first]['name']  . "<br>" ;
				$this->treated[$id_first] = $this->raw[$id_first];
				$level++ ;
				$this->treated[$id_first]['level'] = $level;
				$new_pointer = $id_first ;
				$family_count++ ;
				$this->scan_part_2($new_pointer, $level, true, $id_end, $loop_count, $family_count) ;
			}else{
				$log = $log . "There is no child<br>" ;
				$id_next = $this->raw[$pointer]['id_next'] ;
				if($id_next != 0){
					$log = $log . "There is a little brother : " . $id_next . ". " . $this->raw[$id_next]['name']  .  "<br>" ;
					$this->treated[$id_next] = $this->raw[$id_next];
					$this->treated[$id_next]['level'] = $level;
					$new_pointer = $id_next  ;
					$family_count++ ;
					if($new_pointer==$id_end){
						$log = $log . "We came to last article<br>" ;
						return ;
					}
					$this->scan_part_2($new_pointer, $level, true, $id_end, $loop_count, $family_count) ;
				}else{
					$log = $log . "There is no little brother<br>" ;
					$this->scan_part_2($pointer, $level, false, $id_end, $loop_count, $family_count) ;
				}
			}
		}else{ // On va tester le id_next du id_parent
			$log = $log . "Deeper false. Pointer is still  " . $this->raw[$pointer]['id'] . ". " . $this->raw[$pointer]['name'] . "<br>"  ;
			$id_parent = $this->raw[$pointer]['id_parent'] ; 
			$id_oncle = $this->raw[$id_parent]['id_next'] ; 
			if($id_oncle!=0){
				$log = $log . "There is an oncle : " . $id_oncle . ". " . $this->raw[$id_oncle]['name']  . "<br>" ;
				$this->treated[$id_oncle] = $this->raw[$id_oncle];
				$level-- ;
				$this->treated[$id_oncle]['level'] = $level;
				$new_pointer = $id_oncle  ;
				$family_count++ ;
				if($new_pointer==$id_end){
					$log = $log . "We came to last article<br>" ;
					return ;
				}
				$this->scan_part_2($new_pointer, $level, true, $id_end, $loop_count, $family_count) ;
			}else{
				$log = $log . "There is no oncle<br>" ;
				$level-- ; 
				$this->scan_part_2($id_parent, $level, false, $id_end, $loop_count, $family_count) ;
			}
		}
	}

////////////////////////////////////// RECORDING ARTICLES ///////////////////////////////////////////////////////

	function insert_article($article){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		// Recording content
		$fields = " (id_parent, title, name, abstract, text, id_writer, id_status, published, modified) " ;
		$values = " (" . 
			$article['id_parent'] . ", " . 
			$this->db->quote($article['title']) .  ", " . 
			$this->db->quote($article['name']) .  ", " . 
			$this->db->quote($article['abstract']) .  ", " . 
			$this->db->quote($article['text']) . ", " .
			$article['id_writer'] . ", " .
			$article['id_status'] . ", " .
			" NOW() ,". 
			" NOW()" . ") " ;

		$firstquery = "INSERT INTO " . $this->table  . $fields ." VALUES" . $values ;
		$log = $log . $firstquery . "<br>" ;

		if($retour = $this->db->exec($firstquery)){
			$id_current = $this->db->lastInsertId() ;
			$log = $log . "id du nouvel enregistrement : " . $id_current . "<br>" ;
		} else {
			$log = $log .  "Problème d enregistrement du contenu : " .
				$this->db->errorCode() . " <br>" ;
				var_dump($this->db->errorInfo()) ;
			return false ;
		}

		// Recording environement
		$scan=$this->scan_articles() ;
		$id_parent  = $article['id_parent'] ; 
		$log = $log . "id parent apres enregistrement " . $id_parent . "<br>" ;

		$id_first = $scan[$id_parent]['id_first'] ;  
		$id_last = $scan[$id_parent]['id_last'] ;  

		$querys[] =  "UPDATE  " . $this->table . " SET id_last='" . $id_current . "' WHERE id='" . $id_parent . "'";
		$querys[] =  "UPDATE  " . $this->table . " SET id_previous='" . $id_last . "' WHERE id='" . $id_current . "'";

		if($id_last!=0){
			$log = $log . "Test id new big brother : il est différent de 0.<br>" ;
			$querys[] =  "UPDATE  " . $this->table . " SET id_next='" . $id_current . "' WHERE id='" . $id_last . "'" ;
		}else{
			$log = $log . "Test id new little brother : else au il est différent de 0.<br>" ;
			$querys[] =  "UPDATE  " . $this->table . " SET id_first='" . $id_current . "' WHERE id='" . $id_parent . "'" ;
			$querys[] =  "UPDATE  " . $this->table . " SET id_last='" . $id_current . "' WHERE id='" . $id_parent . "'" ;
		}


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
		if($this->db->errorCode()==PDO::ERR_NONE){
			return $id_current ;
		}
	}

	function update_content($content){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$query = 
			"UPDATE " . $this->table . " " .
			"SET " .
				"title=" . $this->db->quote($content['title']) . ", " .
				"name=" . $this->db->quote($content['name']) . ", " .
				"abstract=" . $this->db->quote($content['abstract']) . ", " .
				"text=" . $this->db->quote($content['text']) . ", " .
				"id_writer=" . $this->db->quote($content['id_writer']) . ", " .
				"id_status=" . $this->db->quote($content['id_status']) . ", " .
				"modified= NOW() " .
			"WHERE id=" . $this->db->quote($content['id']) ;
			$log = $log . $query . "<br>" ;
/*
		if($_SESSION['id_privilege']==ADMIN){
		echo "session : " ; var_dump($_SESSION) ; echo "<br><br>" ;
		echo "content : " ; var_dump($content) ; echo "<br><br>" ;	
		echo "requête : " . $query . "<br><br>" ;	
		}			
*/
		try{
			$retour = $this->db->exec($query) ;
		}catch(Exception $e){
			echo "Problème lors de la mise à jour<br>" ;
			echo $query ;
			die($e->getMessage()) ;
		}


/*
		if($retour = $this->db->exec($query)){
			return true ;
		} else {
			$log = $log .  "Foirage sur la ligne." .
				$this->db->errorCode() . " <br>" ;
			return false ;
		}
*/
	}

	function move_by_parent($id_current, $id_new_parent){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$querys = array() ;

		$scan=$this->scan_articles() ;
		$id_old_parent = $scan[$id_current]['id_parent'] ;
		$id_old_parent_first = $scan[$id_old_parent]['id_first'] ;
		$id_old_parent_last = $scan[$id_old_parent]['id_last'] ;
		$id_old_bigbrother = $scan[$id_current]['id_previous'] ;
		$id_old_littlebrother = $scan[$id_current]['id_next'] ;
		$id_new_parent_first = $scan[$id_new_parent]['id_first'] ;
		$id_new_parent_last = $scan[$id_new_parent]['id_last'] ;


		// Removing current article from actual place
		if($id_old_bigbrother!=0){
			$querys[] = "UPDATE  " . $this->table . " SET id_next=" . $id_old_littlebrother . " WHERE id=" . $id_old_bigbrother ; 
		}
		if($id_old_littlebrother!=0){
			$querys[] = "UPDATE  " . $this->table . " SET id_previous=" . $id_old_bigbrother . " WHERE id=" . $id_old_littlebrother ; 
		}
		if($id_old_parent_first==$id_current){
			$querys[] = "UPDATE  " . $this->table . " SET id_first=" . $id_old_littlebrother . " WHERE id=" . $id_old_parent ; 
		}
		if($id_old_parent_last==$id_current){
			$querys[] = "UPDATE  " . $this->table . " SET id_last=" . $id_old_bigbrother . " WHERE id=" . $id_old_parent ; 
		}

		// Placing current article at its new place, as last child of its new parent
		$querys[] =  "UPDATE  " . $this->table . " SET id_parent=" . $id_new_parent . " WHERE id=" . $id_current ;
		$querys[] =  "UPDATE  " . $this->table . " SET id_previous=" . $id_new_parent_last . " WHERE id=" . $id_current ;
		$querys[] =  "UPDATE  " . $this->table . " SET id_next=0 WHERE id=" . $id_current ;
		$querys[] =  "UPDATE  " . $this->table . " SET id_last=" . $id_current . " WHERE id=" . $id_new_parent ;
		if($id_new_parent_last==0){
			$querys[] =  "UPDATE  " . $this->table . " SET id_first=" . $id_current . " WHERE id=" . $id_new_parent ;
		}else{
			$querys[] =  "UPDATE  " . $this->table . " SET id_next=" . $id_current . " WHERE id=" . $id_new_parent_last ;
		}

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
		if($this->db->errorCode()==PDO::ERR_NONE){
			return true ;
		}
	}

	function move_by_brother($id_current, $id_new_littlebrother){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		$querys=array() ;
		$scan=$this->scan_articles() ;

		$id_parent =  $scan[$id_current]['id_parent'] ;

		$id_new_bigbrother = $scan[$id_new_littlebrother]['id_previous'] ;

		$id_old_bigbrother = $scan[$id_current]['id_previous'] ;
		$id_old_littlebrother = $scan[$id_current]['id_next'] ;

		if($id_current == $scan[$id_parent]['id_first']){
			$querys[] = "UPDATE  " . $this->table . " SET id_first=" . $id_old_littlebrother . " WHERE id=" . $id_parent ;
		}
		if($id_current == $scan[$id_parent]['id_last']){
			$querys[] = "UPDATE  " . $this->table . " SET id_last=" . $id_old_bigbrother . " WHERE id=" . $id_parent ;
		}
		if($id_old_bigbrother!=0){
			$querys[] = "UPDATE  " . $this->table . " SET id_next=" . $id_old_littlebrother . " WHERE id=" . $id_old_bigbrother ;
		}
		if($id_old_littlebrother!=0){
			$querys[] = "UPDATE  " . $this->table . " SET id_previous=" . $id_old_bigbrother . " WHERE id=" . $id_old_littlebrother ;
		}

		if($id_new_bigbrother!=0){
			$querys[] = "UPDATE  " . $this->table . " SET id_next=" . $id_current . " WHERE id=" . $id_new_bigbrother ;
		}else{
			$querys[] = "UPDATE  " . $this->table . " SET id_first=" . $id_current . " WHERE id=" . $id_parent ;
		}

		if($id_new_littlebrother!=0){
			$querys[] = "UPDATE  " . $this->table . " SET id_previous=" . $id_current . " WHERE id=" . $id_new_littlebrother ;
		}
		$querys[] = "UPDATE  " . $this->table . " SET id_next=" . $id_new_littlebrother . " WHERE id=" . $id_current ;
		$querys[] = "UPDATE  " . $this->table . " SET id_previous=" . $id_new_bigbrother . " WHERE id=" . $id_current ;

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
		if($this->db->errorCode()==PDO::ERR_NONE){
			return true ;
		}
	}

	function delete_article($id_article){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$querys=array() ;
		$scan=$this->scan_articles() ;

		$id_parent = $scan[$id_article]['id_parent'] ;
		$id_parent_first = $scan[$id_parent]['id_first'] ;
		$id_parent_last = $scan[$id_parent]['id_last'] ;
		$id_bigbrother = $scan[$id_article]['id_previous'] ;
		$id_littlebrother =  $scan[$id_article]['id_next'] ;

		if($id_bigbrother!=0){
			$querys[] = "UPDATE  " . $this->table . " SET id_next=" . $id_littlebrother . " WHERE id=" . $id_bigbrother ; 
		}
		if($id_littlebrother!=0){
			$querys[] = "UPDATE  " . $this->table . " SET id_previous=" . $id_bigbrother . " WHERE id=" . $id_littlebrother ; 
		}
		if($id_parent_first==$id_article){
			$querys[] = "UPDATE  " . $this->table . " SET id_first=" . $id_littlebrother . " WHERE id=" . $id_parent ; 
		}
		if($id_parent_last==$id_article){
			$querys[] = "UPDATE  " . $this->table . " SET id_last=" . $id_bigbrother . " WHERE id=" . $id_parent ; 
		}

		$querys[] = "DELETE FROM $this->table WHERE id=$id_article" ;

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
		//if($this->db->errorCode()==PDO::ERR_NONE){
			return $id_parent  ;
		//}
	}


///////////////////////////////// SET OF LINKS /////////////////////////////////////////////
	
	function select_ext_links($id_article){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$resulttab=array(); $extlinktab=array();
		$query="SELECT ext_links FROM " . $this->table . " WHERE id=" . $id_article ;
		$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;


		if($resulttab=unserialize($result['ext_links'])){
			foreach($resulttab as $index=>$r){
				$extlinktab[$index]['name']=$r['name'] ;
				$extlinktab[$index]['url']=$r['url'] ;
			}
		}
		return $extlinktab ;
	}

	function insert_ext_link($link){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$resulttab=array(); $extlinktab=array();
		$query="SELECT ext_links FROM " . $this->table . " WHERE id=" . $link['id_article'] ;
		$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
		if($resulttab=unserialize($result['ext_links'])){
			foreach($resulttab as $index=>$r){
				$extlinktab[$index]['name']=$r['name'] ;
				$extlinktab[$index]['url']=$r['url'] ;
			}
		}
		$linkinsert=array('name'=>$link['name'], 'url'=>$link['url']) ;
		$extlinktab[]=$linkinsert;

		$extlinkstring=serialize($extlinktab);
		$query = "UPDATE  " . $this->table . " SET ext_links='" . $extlinkstring . "' WHERE id=" . $link['id_article'] ;
		$log = $log . $query . "<br>" ;
		if($retour = $this->db->exec($query)){
			$log = $log . "Enregistrement réussi.<br>" ;
			return true ;
		} else {
			$log = $log .  "Problème d enregistrement : " .
				$this->db->errorCode() . " <br>" ;
				var_dump($this->db->errorInfo()) ;
			return false ;
		}
	}

	function delete_ext_link($id_article, $id_link){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;


		$extlinktab = array() ;
		$query="SELECT ext_links FROM " . $this->table . " WHERE id=" . $id_article ;
		$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
		if($resulttab=unserialize($result['ext_links'])){
			foreach($resulttab as $index=>$r){
				if($index!=$id_link){$extlinktab[$index]=$r ;}
			}
		}

		$extlinkstring=serialize($extlinktab);

		$query = "UPDATE  " . $this->table . " SET ext_links='" . $extlinkstring . "' WHERE id=" . $id_article ;
		echo $query . "<br>";
		$log = $log . $query . "<br>" ;
		if($retour = $this->db->exec($query)){
			$log = $log . "Enregistrement réussi.<br>" ;
			return true ;
		} else {
			$log = $log .  "Problème d enregistrement : " .
				$this->db->errorCode() . " <br>" ;
				var_dump($this->db->errorInfo()) ;
			return false ;
		}
	}

	function select_int_links($id_article){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$resulttab=array(); $intlinktab=array();
		$query="SELECT int_links FROM " . $this->table . " WHERE id=" . $id_article ;
		$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;

		if($resulttab=unserialize($result['int_links'])){
			foreach($resulttab as $r){
				$intlinktab[$r]=$this->select_one_name($r) ;
			}
		}
		return $intlinktab ;
	}

	function insert_int_link($link){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$intlinktab = array() ;
		$query="SELECT int_links FROM " . $this->table . " WHERE id=" . $link['id_article'] ;
		$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
		if($resulttab=unserialize($result['int_links'])){
			foreach($resulttab as $index=>$r){
				$intlinktab[$index]=$r ;
			}
		}

		$intlinktab[]=$link['id_linked_article'] ;

		$intlinkstring=serialize($intlinktab);
		$query = "UPDATE  " . $this->table . " SET int_links='" . $intlinkstring . "' WHERE id=" . $link['id_article'] ;
		$log = $log . $query . "<br>" ;
		if($retour = $this->db->exec($query)){
			$log = $log . "Enregistrement réussi.<br>" ;
			return true ;
		} else {
			$log = $log .  "Problème d enregistrement : " .
				$this->db->errorCode() . " <br>" ;
				var_dump($this->db->errorInfo()) ;
			return false ;
		}		
	}

	function delete_int_link($id_article, $id_link){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;

		$intlinktab = array() ;
		$query="SELECT int_links FROM " . $this->table . " WHERE id=" . $id_article ;
		$result = $this->db->query($query)->fetch(PDO::FETCH_ASSOC) ;
		if($resulttab=unserialize($result['int_links'])){
			foreach($resulttab as $index=>$r){
				if($r!=$id_link){$intlinktab[$index]=$r ;}
			}
		}

		$intlinkstring=serialize($intlinktab);
		$query = "UPDATE  " . $this->table . " SET int_links='" . $intlinkstring . "' WHERE id=" . $id_article ;
		$log = $log . $query . "<br>" ;
		if($retour = $this->db->exec($query)){
			$log = $log . "Enregistrement réussi.<br>" ;
			return true ;
		} else {
			$log = $log .  "Problème d enregistrement : " .
				$this->db->errorCode() . " <br>" ;
				var_dump($this->db->errorInfo()) ;
			return false ;
		}
	}

	function select_links($id){
		global $log ; $log = $log . "<b>" .  __METHOD__  . "</b><br>" ;
		if(is_numeric($id)){
			$query = "SELECT id_parent, id_previous, id_next, id_first, id_last " .
				"FROM " . $this->table . " " .
				"WHERE id=" . $this->db->quote($id) ;
			$log = $log . $query . "<br>" ;
			if($links = $this->db->query($query)->fetch(PDO::FETCH_ASSOC)){

				 /* The parent */
				 $parent=array();
				 if($links['id_parent']>0){
				 	$parent[$links['id_parent']]=$this->select_one_name($links['id_parent'] ) ;
				 }

				 /* The brothers */
				 $brothers=array() ;
				$query = "SELECT id, name " .
						"FROM " . $this->table . " " .
						"WHERE id_parent=" . $links['id_parent'] ;
				$result = $this->db->query($query) ;
				$result->setFetchMode(PDO::FETCH_ASSOC) ;
				foreach($result as $r){
					$brothers[$r['id']] = $r['name'] ;
				}
			 
			 	/* The children */
			 	$children=array() ;
				$query = "SELECT id, name " .
						"FROM " . $this->table . " " .
						"WHERE id_parent=" . $id ;
				$result = $this->db->query($query) ;
				$result->setFetchMode(PDO::FETCH_ASSOC) ;
				foreach($result as $r){
					$children[$r['id']] = $r['name'] ;
				}

				/* External links */
				$ext_links=$this->select_ext_links($id) ;

				/* Internal links */
				$int_links=$this->select_int_links($id) ;

				/* Getting all together */
			 	$set_of_links=array(
			 			'parent'=>$parent,
			 			'brothers'=>$brothers,
			 			'children'=>$children,
			 			'ext_links'=>$ext_links,
			 			'int_links'=>$int_links
			 			) ;
				 return $set_of_links ;
			}
		}
	}

	/////////////////////////////////////// ADDITIONAL METHODS ////////////////////////////////////////////
	static function get_DefaultArticle($id_parent){
		global $log, $language ;
		$log = $log . '<b>' .   __METHOD__  . "</b><br>" ;
		$default_article['id'] = NO_ID ;
		$default_article['id_parent'] = $id_parent ;
		$default_article['title'] = "Titre de nouvel article"; 
		$default_article['name'] = "Nom de nouvel article" ;
		$default_article['abstract'] = "Nouveau abstract" ;
		$default_article['text'] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean sed pulvinar ipsum. Aenean id odio quam. Vestibulum eget aliquet diam. Curabitur placerat consequat urna quis." ;
		$default_article['id_writer'] = 1 ;
		$default_article['id_status'] =0 ;
		$default_article['published'] = "0000-00-00 00:00:00" ;
		$default_article['modified'] = "0000-00-00 00:00:00" ;
		return $default_article ;	
	}

	private function insertFirstRows(){
		global $log ; $log = $log . '<b>' .   __METHOD__  . "</b><br>" ;

		// This method will be excuted if the article database table is empty
		$autoincrement = "ALTER TABLE " . $this->table . " AUTO_INCREMENT=0" ;
		$fields = " (title, name, text, id_writer, published, modified, id_status, id_parent, id_previous, id_next, id_first, id_last) " ;
		$homepage="(" .
			"'Homepage'" . ", " .
			"'Homepage'" .  ", " .
			"'Lorem ipsum dolor sit amet,'" . ", " .
			"1" . ", " .		// writer
			" NOW() ,". 
			" NOW() ," . 
			"3" . ", " .		// id_status
			"0" . ", " . 		// id_parent
			"0" . ", " .		// id_previous
			"2" . ", " .		// id_next
			"0" . ", " .		// id_first
			"0" . ") " ;		// id_last

		$plan="(" .
			"'Plan'" . ", " .
			"'Plan'" .  ", " .
			"'Lorem ipsum dolor sit amet,'" . ", " .
			"1" . ", " .		// writer
			" NOW() ,". 
			" NOW() ," . 
			"3" . ", " .		// id_status
			"0" . ", " . 		// id_parent
			"1" . ", " .		// id_previous
			"0" . ", " .		// id_next
			"0" . ", " .		// id_first
			"0" . ") " ;		// id_last


		$querys=array(
			//0 => $autoincrement,
			1 => "INSERT INTO " . $this->table  . $fields ." VALUES" . $homepage ,
			2 => "INSERT INTO " . $this->table  . $fields ." VALUES" . $plan ,
			);

		foreach($querys as $query){
			$log = $log . $query . "<br>" ;
			if($retour = $this->db->exec($query)){
				$id_current = $this->db->lastInsertId() ;
				$log = $log . "id du nouvel enregistrement : " . $id_current . "<br>" ;
			} else {
				$log = $log .  "Problème d'enregistrement des lignes par défaut.<br>" .
					$this->db->errorCode() . " <br>" ;
				return false ;
			}
		}
	}





	function outputraw(){
		foreach($this->raw as $key => $rawitem){
			echo $rawitem['id'] . ". " . $rawitem['name'] . 
				" - id first : " . $rawitem['id_first'] . " ; " . 
				" - id next : " . $rawitem['id_next'] . "<br>";
		}
	}

	function tabulations($t){
		$margin = $t * 15 ;
	return "style='margin-left:" .  $margin . "px;'" ;


	}
}
?>
