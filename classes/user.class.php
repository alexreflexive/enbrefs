<?php
class Internetuser{
	private $id ;
	private $pseudo ;
	private $email ;
	private $id_privilege ;
	private $devise ;
	private $language ;

	function Internetuser($surfer){
		$this->id = $surfer['id'] ;
		$this->pseudo = $surfer['pseudo'] ;
		$this->email = $surfer['email'] ;
		$this->id_privilege = $surfer['id_privilege'] ;
		$this->devise = $surfer['devise'] ;
		$this->language = $surfer['language'] ;
	}

	function get_IdMember(){
		return $this->id ;
	}

	function get_Pseudo(){
		return $this->pseudo ;
	}

	function get_Email(){
		return $this->email ;
	}

	function get_Privilege(){
		return $this->id_privilege ;
	}

	function get_Devise(){
		return $this->devise ;
	}

	function get_Language(){
		return $this->language ;
	}

	function get_UserArray($user){
		$userarray = array() ;
		$userarray['id']= $user->id ;
		$userarray['pseudo']= $user->pseudo ;
		$userarray['email']= $user->email ;
		$userarray['id_privilege']= $user->id_privilege ;
		$userarray['devise']= $user->devise ;
		$userarray['language']= $user->language ;
	}

	static function get_DefaultUser(){
		global $log, $lg, $lang ;
		$log = $log . '<b>' .   __METHOD__  . "</b><br>" ;
		$default_user['id'] = NO_ID ;
		$default_user['pseudo'] = NOTHING; 
		$default_user['email'] = NOTHING ;
		$default_user['id_privilege'] = VISITOR ;
		$default_user['devise'] = NOTHING ;
		$default_user['language'] =$lg ;
		return $default_user ;	
	}



}



?>