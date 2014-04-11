<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_login extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;
	protected $_name;
	protected $_user_profile;

	public function __construct() {
		$this->_name = "user";
		$this->_user_profile = 'user_profile';
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
		
	}
	
	public function login($username,$password){
		$username		= trim($username);
		$password		= md5($password);
		$user 			= $this->_getUserInfoByUsername($username);
		$userid 		= $user['userid'];
		$pass 			= trim($user['pass']);
		if($pass === $password){
			return $user;
		}else{
			return false;
		}
	}
	
	private function _getUserInfoByUsername($username){
		$sql = " SELECT us.*,role.role_name 
				 FROM user us
				 INNER JOIN user_roles role ON us.role_id = role.role_id
				 WHERE us.user_name = ? AND us.active = 1 LIMIT 1";
		return $this->_db->fetchRow($sql,array($username));
	}
	
	public function getRandomUsers($limit){
		$ids = $this->getUserIds($limit);
		$sql = " SELECT * FROM user WHERE userid IN (".implode(',', $ids).")";
		return $this->_db->fetchAll($sql);
	}
	
	private function getUserIds($limit){
		$ids = array();
		$commentIds = array();
		$sql = " SELECT userid FROM user WHERE active = 1 LIMIT 1000,3000";
		$idList = $this->_db->fetchAll($sql);
		foreach((array)$idList as $idData){
			$ids[] = $idData['userid'];
		}
		
		while(sizeof($commentIds) < $limit){
			$index = rand(0,sizeof($ids)-1);
			if(!in_array($index,$commentIds)){
				$commentIds[] = $index;
			}
		}
		return $commentIds;
	}
	
	public function getAll($where){
		$sql = "select * from user where ".$where;
		$retval = $this->_db->fetchAll($sql);
		return $retval;
	}
	public function findByUserName($user_name){
		$sql = "select * from user where user_name = '$user_name' and active = 0";
		$retval = $this->_db->fetchRow($sql);
		return $retval;
	}
	public function findById($id){
		$sql = "select Ur.*, Up.* from user Ur left join user_profile Up on Ur.user_name = Up.user_name where userid = '$id'";
		$retval = $this->_db->fetchRow($sql);
		return $retval;
	}
	function deleteUser($user_name){
		$where = $this->_db->quoteInto("user_name = ?",$user_name);
		return $this->delete($where);
	}
	
	function updateData($data,$user_name){
		$where = $this->_db->quoteInto("user_name = ?",$user_name);
		return $this->update($data,$where);
	}
	
	function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	}
	
	public function checkExistsUsername($username,$userId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$username 		= addslashes(strtolower($username));
		if($userId >0){
			$sql 		= "SELECT COUNT(userid) FROM user WHERE user_name = ? AND user_id <>  ?";
			return $this->_db->fetchOne($sql,array($username,$userId));
		}else{
			$sql 		= "SELECT COUNT(userid) FROM user WHERE user_name = ?";
			return $this->_db->fetchOne($sql,array($username));
		}
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(user_name) FROM User";
		return  (int)$this->_db->fetchOne($sql);
	}
	
	public function getUserById($userid,$filter = array()){
		$sqlPlus = $this->_getUser_sqlPlus($filter);
		$sqlJoin	= $this->_getUser_sqlJoin($filter);
		$sql = " SELECT us.*, CONCAT(us.firstname,' ',us.lastname) fullname $sqlPlus
		FROM user us $sqlJoin
		WHERE us.userid = ? ";
		$user 	= $this->_db->fetchRow($sql,array($userid));
		$this->_getUser_More($user,$filter);
		$user = $this->tooObject($user);
		return $user;
	}
	
	public function getUser($username,$filter = array()) {
		$sqlPlus = $this->_getUser_sqlPlus($filter);
		$sqlJoin	= $this->_getUser_sqlJoin($filter);
		$sql = " SELECT us.*, CONCAT(us.firstname,' ',us.lastname) fullname $sqlPlus
				FROM user us $sqlJoin
				WHERE us.user_name= ? ";
		$user 	= $this->_db->fetchRow($sql,array($username));
		$this->_getUser_More($user,$filter);
		$user = $this->tooObject($user);
		return $user;
	}
	
	function tooObject($dataArray){
	    if(!is_array($dataArray)){return $dataArray;}
	    $dataObject = new stdClass;
	    foreach($dataArray as $key => $value){
	        $dataObject->$key = (is_array($value)) ? $this->tooObject($value) : $value;
	    }
	    return $dataObject;
	}
	
	private function _getUser_sqlPlus($filter = array()){
		$sqlPlus = null;
		foreach((array)$filter as $key => $val){
			switch($key){
				case 'get_blood_type':
					$sqlPlus .= ', blt.blood_type as blood_type_name';
				break;
			}
		}
		return $sqlPlus;
	}
	
	private function _getUser_sqlJoin($filter = array()){
		$sqlJoin = null;
		foreach((array)$filter as $key => $val){
			switch($key){
				case 'get_blood_type':
					$sqlJoin .= ' LEFT JOIN blood_type blt on us.blood_type = blt.id ';
					break;
			}
		}
		return $sqlJoin;
	}
	
	private function _getUser_More(&$user,$filter = array()){
		
	}
	
	public function getListUser_nb($filter = array()) {
		$sqlPlus = $this->getListUser_sqlPlus($filter);
		$sql = "SELECT COUNT(us.userid)
				FROM user us
				LEFT JOIN user_roles ur ON ur.role_id = us.role_id
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListUser($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListUser_sqlPlus($filter);
		$sql = "SELECT us.*,ur.role_name
				FROM user us
				LEFT JOIN user_roles ur ON ur.role_id = us.role_id
				WHERE 1=1 $sqlPlus ORDER BY us.user_name LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListUser_sqlPlus($filter){
		$sqlPlus = null;
		if(isset($filter['keyword'])){
			$keyword = addslashes(trim(@$filter['keyword']));
			$sqlPlus .= " AND (us.user_name LIKE '%$keyword%' OR
								us.emergency_code LIKE '%$keyword%' OR
								us.email LIKE '%$keyword%' OR
								us.firstname LIKE '%$keyword%' OR
								us.lastname LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	/**
	 * @description : update user
	 * @param unknown $data
	 * @param unknown $user_name
	 * @return Ambigous <boolean, number>
	 */
	function updateUserprofile($data,$user_name){
		//zf_debug($data); die();
		if (!empty($user_name) && !is_array($data) ) {
			return false;
		}
		$update = false;
		$checkuser = $this->getUserByName($username);
		if (is_array($checkuser)) {
			$update = $this->_db->update($this->_user_profile, $data,$this->getAdapter()->quoteInto("user_name = ?", $user_name));
		}else{
			$data = array_merge($data,array('user_name'=>$user_name,'time'=>time()));
			//zf_debug($data); die();
			$this->_db->insert($this->_user_profile, $data);	
		} 
		return $update;
	}
	/**
	 * @description : get user by name
	 * @param unknown $username
	 */
	public function getUserByName($username){
		$data_user = false;
		$query = $this->_db->select()
				->from($this->_user_profile,'*')
				->where($this->getAdapter()->quoteInto("user_name = ?", $username));
		$data = $this->_db->fetchRow($query);
		
		if (is_array($data)) {
			$data_user = $data;
		}
		return $data_user;			
	}
}

?>
