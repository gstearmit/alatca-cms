<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_action extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "zend_actions";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$moduleId = $data['module_id'];
		$actionName = $data['action_name'];
		if(!$this->_checkExistsAction($moduleId, $actionName)){
			$this->insert($data);
			return $this->getMaxId();
		}else{
			return false;
		}
	}
	
	public function getActionByName($actionName,$moduleId){
		$sql = "SELECT action_id FROM zend_actions WHERE action_name = ? AND module_id = ? LIMIT 1";
		return  (int)$this->_db->fetchOne($sql,array($actionName,$moduleId));
	}
	
	private function _checkExistsAction($moduleId,$actionName){
		$sql = "SELECT COUNT(action_id) FROM zend_actions WHERE module_id = ? AND action_name = ?";
		return  (int)$this->_db->fetchOne($sql,array($moduleId,$actionName));
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(action_id) FROM zend_actions";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getAction($actionId,$filter = array()) {
		$sql = "SELECT ac.*,md.module_name,md.module_name_display
				FROM zend_actions ac
				INNER JOIN zend_modules md ON ac.module_id = md.module_id
				WHERE ac.action_id= ".(int)$actionId;
		return $this->_db->fetchRow($sql);
	}
	public function getListAction_nb($filter = array()) {
		$sqlPlus = $this->getListAction_sqlPlus($filter);
		$sql = "SELECT COUNT(ac.action_id)
				FROM zend_actions ac
				INNER JOIN zend_modules md ON ac.module_id = md.module_id
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListAction($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListAction_sqlPlus($filter);
		$sql = "SELECT ac.*,md.module_name,md.module_name_display
				FROM zend_actions ac
				INNER JOIN zend_modules md ON ac.module_id = md.module_id
				WHERE 1=1 $sqlPlus ORDER BY md.module_name LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListAction_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		if($keyword){
			$sqlPlus .= " AND (md.module_name LIKE '%$keyword%' OR md.module_name_display LIKE '%$keyword%' OR ac.action_name LIKE '%$keyword%' OR ac.action_name_display LIKE '%$keyword%' OR ac.description LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	
}

?>
