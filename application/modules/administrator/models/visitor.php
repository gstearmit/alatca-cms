
<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_visitor extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "tracking_visitor";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	
	public function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	}
	public function getListall($query) {
		return $this->_db->fetchAll($query);
	}
	
	
	public function getVisitorByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT visitor_id FROM tracking_visitor WHERE name = ? ORDER BY id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	public function getMaxId(){
		$sql = "SELECT MAX(id) FROM tracking_url";
		return  (int)$this->_db->fetchOne($sql);
	}
	
	public function updateData($data,$VisitorId){
		$VisitorKey = $data['name'];
		if(!$this->_checkExistsKey($VisitorKey,$VisitorId)){
			$this->update($data,'id = '.(int)$VisitorId);
			return $VisitorId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$VisitorId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($VisitorId >0){
			$sql 		= "SELECT COUNT('id') FROM tracking_visitor  WHERE name REGEXP BINARY '$key' AND id <> ".(int)$VisitorId;
		}else{
			$sql 		= "SELECT COUNT('id') FROM tracking_visitor  WHERE name REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	

	public function getVisitor($VisitorId,$filter = array()) {
		$sql = " SELECT * FROM tracking_visitor WHERE id= ".(int)$VisitorId;
		return $this->_db->fetchRow($sql);
	}
	public function getListVisitor_nb($filter = array()) {
		$sqlPlus = $this->getListVisitor_sqlPlus($filter);
		$sql = "SELECT COUNT(id)
				FROM tracking_visitor
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListVisitor($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListVisitor_sqlPlus($filter);
		$sql = "SELECT *
				FROM tracking_visitor 
				WHERE 1=1 $sqlPlus ORDER BY name ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListVisitor_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND ( name LIKE '%$keyword%' OR visitor_id LIKE '%$keyword%' OR email LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR code LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	
}

?>
