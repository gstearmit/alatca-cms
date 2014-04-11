<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_newscategory extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "category";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	}
	/*
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
	*/
	
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
		$sql = "SELECT MAX(id) FROM category";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getNewscategory($typeId,$filter = array()) {
		$sql = " SELECT * FROM category WHERE id= ".(int)$typeId;
		return $this->_db->fetchRow($sql);
	}
	public function getListNewscategory_nb($filter = array()) {
		$sqlPlus = $this->getListNewscategory_sqlPlus($filter);
		$sql = "SELECT COUNT(id)
		FROM category cate
				INNER JOIN news_group grp ON cate.group_id = grp.group_id
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListNewscategory($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListNewscategory_sqlPlus($filter);
		$sql = "SELECT cate.*, grp.group_name
				FROM category cate
				INNER JOIN news_group grp ON cate.group_id = grp.group_id
				WHERE 1=1 $sqlPlus ORDER BY grp.group_name ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListNewscategory_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND (cate.category_name LIKE '%$keyword%' OR cate.description LIKE '%$keyword%' OR grp.group_name LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	
}

?>
