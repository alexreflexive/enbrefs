<?php
require_once('classes/preferences.mysql.class.php') ;
require_once('classes/user.class.php') ;
require_once('classes/article.mysql.class.php') ;
require_once('classes/member.mysql.class.php') ;
require_once('classes/pm.mysql.class.php') ;

class MySQL {
	private $salt ;
	public $preferences ;

	function MySQL($autorisations){
		global $log ;
			$serveur = $autorisations['host'] ;
			$base = $autorisations['base'] ;
			$dsn = 'mysql:host=' . $serveur . ';dbname=' . $base . ';charset=UTF8' ;
			$utilisateur = $autorisations['user'] ;
			$mdp = $autorisations['password'] ;
			$prefix = $autorisations['prefix'] ;
			$this->salt = $autorisations['salt'] ;


			try {
				$db= new PDO($dsn, $utilisateur, $mdp) ;
		       		//$db->exec('SET CHARACTER SET UTF-8');
		       		$log = $log . "la connexion a réussi.<br>" ;
			} catch (PDOException $e) {
				echo "Raté!!! " . $e->getMessage() . "<br>" ;
				die();
			}
			$this->sp=new SitePreferences($db, $prefix) ;
			$this->preference=$this->get_Preferences() ;
			$this->article=new Article($db, $prefix) ;
			$this->member=new Member($db, $prefix) ;
			$this->pm=new private_message($db, $prefix) ;
	}	

	/* Sites preferences */
	function get_Preferences(){
		return $this->sp->select_one(1) ;
	}

	function modify_Preferences($p){
		return $this->sp->update_preferences($p);
	}


	/* Creation de l'objet user et son renvoi */
	function CreateUser($session){
		$this->user=new Internetuser($session) ;
		return $this->user ;
	}
	
	/* Accès à inscrits */
	function get_members(){
		$members = $this->member->select_all() ;
		return $members ;
	}

	function get_member($id_member){
		$member = $this->member->select_one($id_member) ;
		return $member ;
	}

	function get_MemberCollection($page, $rows_per_page){
		$collection = $this->member->select_collection($page, $rows_per_page) ;
		return $collection ;
	}

	function create_member($user){
		return $this->member->insert_values($user, $this->salt) ;
	}


	function modify_personalprofile($recuser){
		return $this->member->update_personalprofile($recuser) ;
	}

	function modify_secretprofile($recuser){
		return $this->member->update_secretprofile($recuser, $this->salt) ;
	}

	function modify_socialprofile($recuser){
		return $this->member->update_socialprofile($recuser) ;
	}

	function ValidateLogin($login){
		return $this->member->test_login($login, $this->salt) ;
	}

	function CheckPassword($id, $password){
		return $this->member->test_password($id, $password, $this->salt);
	}

	function get_UserArray($user){

	}

	function IsPseudoAviable($pseudo){
		return $this->member->pseudo_aviable($pseudo) ;
	}

	/* Accès à articles */
	function article_monitoring(){
		return $this->article->scan_articles();
	}

	function get_articles(){
		$articles = $this->article->select_all() ;
		return $articles ;
	}

	function get_article($id_article){
		if($id_article>0){
			$article = $this->article->select_one($id_article) ;
			return $article ;
		}else{
			return false ;
		}
	}

	function get_ext_links($id_article){
		return $this->article->select_ext_links($id_article) ;
	}

	function get_int_links($id_article){
		return $this->article->select_int_links($id_article) ;
	}

	function get_set_of_links($id_article){
		if($id_article>0){
			$set = $this->article->select_links($id_article) ;
			return $set ;
		}else{
			return false ;
		}
	}

	function create_ext_link($link){
		return $this->article->insert_ext_link($link) ;
	}

	function modify_ext_link($link){
		return $this->article->update_ext_link($link) ;
	}

	function remove_ext_link($id_article, $id_link){
		return $this->article->delete_ext_link($id_article, $id_link) ;
	}

	function create_int_link($link){
		return $this->article->insert_int_link($link) ;
	}

	function remove_int_link($id_article, $id_link){
		return $this->article->delete_int_link($id_article, $id_link) ;
	}

	function get_articles_FilterAutor($id_writer, $page, $rows_per_page){
		return $this->article->select_articles_fWriter($id_writer, $page, $rows_per_page) ;
	}

	function get_articles_search($page, $rows_per_page){
		return $this->article->select_articles_search($page, $rows_per_page) ;
	}

	function rec_new_article($content){
		return $this->article->insert_article($content) ;
	}

	function rec_article_content($content){
		return $this->article->update_content($content) ;

	}

	function del_article($id_article){
		return $this->article->delete_article($id_article) ;
	}

	function change_parent($id_current, $id_new_parent){
		return $this->article->move_by_parent($id_current, $id_new_parent) ;
	}

	function change_brother($id_current, $id_new_bigbrother){
		return $this->article->move_by_brother($id_current, $id_new_bigbrother) ;
	}

	/* Private messages */
	function get_pm_inbox($id_to, $page, $rows_per_page){
		return $this->pm->select_pm_to($id_to, $page, $rows_per_page) ;
	}

	function get_onepm_in($id_pm){
		//return $this->pm->select_pm_from($id_from, $page, $rows_per_page) ;
		$pm = $this->pm->select_onein($id_pm) ;
		$from_array = $this->member->select_one($pm['id_from']) ;
		$to_array =$this->member->select_one($pm['id_to']) ;
		$pm['from_pseudo']= $from_array['pseudo'] ;
		$pm['to_pseudo']= $to_array['pseudo'] ;
		$pm['pmbox']=INBOX_MESSENGER ;

		return  $pm;		
	}

	function get_pm_sent($id_from, $page, $rows_per_page){
		return $this->pm->select_pm_from($id_from, $page, $rows_per_page) ;
	}

	function get_onepm_sent($id_pm){
		//return $this->pm->select_pm_from($id_from, $page, $rows_per_page) ;
		$pm = $this->pm->select_onesent($id_pm) ;
		$from_array = $this->member->select_one($pm['id_from']) ;
		$to_array =$this->member->select_one($pm['id_to']) ;
		$pm['from_pseudo']= $from_array['pseudo'] ;
		$pm['to_pseudo']= $to_array['pseudo'] ;
		$pm['pmbox']=SENTBOX_MESSENGER ;

		return  $pm;		
	}

	function rec_pm($pm){
		return $this->pm->insert_pm($pm) ;
	}

	function del_sentpm($pm){
		return $this->pm->delete_sentpm($pm) ;
	}

	function del_inpm($pm){
		return $this->pm->delete_inpm($pm) ;
	}
/*
	function del_pm($pm){
		return $this->pm->delete_pm($pm) ;
	}
*/
/*
	function get_pm_inbox($id_surfer){
		include('pm.mysql.class.php') ;
		$pm_array=array() ;
		if($privatemessages){
			foreach($privatemessages as $index => $pm){
				if($pm['to']==$id_surfer){
					$pm_array[$index] = $pm ;
				}
			}
		return $pm_array ;
		}else{
			return false ;
		}
	}

	function get_pm_sent($id_surfer){
		include('pm.mysql.class.php') ;
		$pm_array=array() ;
		if($privatemessages){
			foreach($privatemessages as $index => $pm){
				if($pm['from']==$id_surfer){
					$pm_array[$index] = $pm ;
				}
			}
			return $pm_array ;
		}else{
			return false ;
		}
	}
*/
	// Pour cette méthode et la suivante on peut se demander 
	//si la construction d'un mp ne serait pas plus du momaine 
	//du contrôleur plutôt que de la base.
	function get_private_message($id_pm){
		$pm = $this->pm->select_one($id_pm) ;
		$from_array = $this->member->select_one($pm['id_from']) ;
		$to_array =$this->member->select_one($pm['id_to']) ;
		$pm['from_pseudo']= $from_array['pseudo'] ;
		$pm['to_pseudo']= $to_array['pseudo'] ;
		return  $pm;
	}
/*
	function get_respond_pm($id_pm){
		include('pm.mysql.class.php') ;
		require ('pseudo_inscrits.php');
		$pm = $privatemessages[$id_pm] ;
		$temp_id = $pm['to'] ;
		$pm['to'] = $pm['from'] ;
		$pm['from']= $temp_id ;
		$pm['from_pseudo']= $members[$pm['from']]['pseudo'];
		$pm['to_pseudo']= $members[$pm['to']]['pseudo'];
		$pm['text'] = "RE:" . $pm['text'] ;
		return  $pm;
	}
*//*
	function get_new_pm($id_surfer, $id_recipient){
		//include('pm.mysql.class.php') ;
		//require ('pseudo_inscrits.php');
		$pm['from_pseudo']= $member[$id_naute]['pseudo'];
		$pm['to_pseudo']= $member[$id_recipient]['pseudo'];
		return  $pm;
	}
*/
}
?>
