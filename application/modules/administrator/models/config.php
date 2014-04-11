<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_config extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "configs";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function getConfigByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT config_value FROM configs WHERE config_key = ? ORDER BY config_id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	
	public function addData($data){
		$configKey = $data['config_key'];
		if(!$this->_checkExistsKey($configKey)){
			$this->insert($data);
			//return $this->getMaxId();
			return "2";
		}else{
			return "-1";
		}
	}
	
	public function updateData($data,$configId){
		$configKey = $data['config_key'];
		if(!$this->_checkExistsKey($configKey,$configId)){
			$this->update($data,'config_id = '.(int)$configId);
			return "1";
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$configId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($configId >0){
			$sql 		= "SELECT COUNT('config_id') FROM configs WHERE config_key REGEXP BINARY '$key' AND config_id <> ".(int)$configId;
		}else{
			$sql 		= "SELECT COUNT('config_id') FROM configs WHERE config_key REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(config_id) FROM configs";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getConfig($configId,$filter = array()) {
		$sql = " SELECT * FROM configs WHERE config_id= ".(int)$configId;
		return $this->_db->fetchRow($sql);
	}
	public function getListConfig_nb($filter = array()) {
		$sqlPlus = $this->getListConfig_sqlPlus($filter);
		$sql = "SELECT COUNT(cfs.config_id)
				FROM configs cfs
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListConfig($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListConfig_sqlPlus($filter);
		$sql = "SELECT cfs.*
				FROM configs cfs
				WHERE 1=1 $sqlPlus ORDER BY cfs.config_key ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListConfig_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND (cfs.config_key LIKE '%$keyword%' OR cfs.description LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	public function getValueKeyConfig($querry) {
		return $this->_db->fetchOne($querry);
	}
	
}

?>
