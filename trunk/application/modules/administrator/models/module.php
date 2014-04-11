<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_module extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "zend_modules";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$moduleName 		= $data['module_name'];
		$moduleNameDisplay 	= $data['module_name_display'];
		if(!$this->_checkExistsModule($moduleName)){
			$this->insert($data);
			$moduleId = $this->getMaxId();
			$this->_addDefaultActions($moduleId,$moduleNameDisplay);
			return $moduleId;
		}else{
			return false;
		}
	}
	
	private function _checkExistsModule($moduleName){
		$sql = "SELECT module_id FROM zend_modules WHERE module_name = ? LIMIT 1";
		return  (int)$this->_db->fetchOne($sql,array($moduleName));
	}
	
	private function _addDefaultActions($moduleId,$moduleName=null){
		$this->_name = "zend_actions";
		$data = array('module_id'=>$moduleId,'action_name'=>'index','action_name_display'=>'Truy cập','description'=>$moduleName);
		$this->insert($data);
		$this->_name = "zend_modules";
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(module_id) FROM zend_modules";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getModule($moduleId,$filter = array()) {
		$sql = " SELECT * FROM zend_modules WHERE module_id= ".(int)$moduleId;
		return $this->_db->fetchRow($sql);
	}
	public function getModuleIdByName($moduleName) {
		$sql = " SELECT module_id FROM zend_modules WHERE module_name = ?";
		return $this->_db->fetchOne($sql,array($moduleName));
	}
	public function getListModule_nb($filter = array()) {
		$sqlPlus = $this->getListModule_sqlPlus($filter);
		$sql = "SELECT COUNT(module_id)
		FROM zend_modules
		WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListModule($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListModule_sqlPlus($filter);
		$sql = "SELECT *
		FROM zend_modules
		WHERE 1=1 $sqlPlus ORDER BY module_id DESC LIMIT $start,$size";
		$modules = $this->_db->fetchAll($sql);
		$this->_getActions($modules);
		return $modules;
	}
	
	private function _getActions(&$modules){
		$ids = array();
		foreach((array)$modules as $module){
			$ids[] = $module['module_id'];
		}
		if(is_array($ids) && sizeof($ids)>0){
			$sql = "SELECT module_id, action_id, action_name_display FROM zend_actions WHERE module_id IN (".implode(',',$ids).")";
			$actions = $this->_db->fetchAll($sql);
			
			for($i=0;$i<sizeof($modules);$i++){
				$module_actions = array();
				foreach((array)$actions as $action){
					if($modules[$i]['module_id'] == $action['module_id']){
						$module_actions[] = $action;
					}
				}
				$modules[$i]['actions'] = $module_actions;
			}
		}
	}
	
	private function getListModule_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		if($keyword){
			$sqlPlus .= " AND (module_name LIKE '%$keyword%' OR `description` LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	
}

?>
