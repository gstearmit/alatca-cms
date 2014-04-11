<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_role extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "user_roles";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(role_id) FROM user_roles";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getRole($roleId,$filter = array()) {
		$sql = " SELECT * FROM user_roles WHERE role_id= ".(int)$roleId;
		return $this->_db->fetchRow($sql);
	}
	public function getListRole_nb($filter = array()) {
		$sqlPlus = $this->getListRole_sqlPlus($filter);
		$sql = "SELECT COUNT(role_id)
		FROM user_roles
		WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListRole($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListRole_sqlPlus($filter);
		$sql = "SELECT *
				FROM user_roles
				WHERE 1=1 $sqlPlus ORDER BY role_id LIMIT $start,$size";
		$listRole = $this->_db->fetchAll($sql);
		for($i=0;$i<sizeof($listRole);$i++){
			$listRole[$i]['users'] = $this->getUserListByRole($listRole[$i]['role_id']);
		}
		return $listRole;
	}
	
	private function getUserListByRole($roleId){
		$sql = "SELECT user_name username,email,concat(lastname,' ',firstname) fullname
				FROM user
				WHERE role_id = ? ORDER BY user_name";
		return $this->_db->fetchAll($sql,array($roleId));
	}
	
	private function getListRole_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		if($keyword){
			$sqlPlus .= " AND (role_name LIKE '%$keyword%' OR `description` LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	
}

?>
