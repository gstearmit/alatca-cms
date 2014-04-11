
<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_dwvisitors extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "dw_visitors";
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
	
	//,name,email	phone,code,last_visit,page_views,time_on_site
	
	public function getVisitorByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT visitor_id FROM dw_visitors WHERE name = ? ORDER BY visitor_id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	public function getMaxId(){
		$sql = "SELECT MAX(visitor_id) FROM dw_visitors";
		return  (int)$this->_db->fetchOne($sql);
	}
	
	public function updateData($data,$VisitorId){
		$VisitorKey = $data['name'];
		if(!$this->_checkExistsKey($VisitorKey,$VisitorId)){
			$this->update($data,'visitor_id = '.(int)$VisitorId);
			return $VisitorId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$VisitorId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($VisitorId >0){
			$sql 		= "SELECT COUNT('visitor_id') FROM dw_visitors  WHERE name REGEXP BINARY '$key' AND visitor_id <> ".(int)$VisitorId;
		}else{
			$sql 		= "SELECT COUNT('visitor_id') FROM dw_visitors  WHERE name REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	

	public function getVisitor($VisitorId,$filter = array()) {
		$sql = " SELECT * FROM dw_visitors WHERE visitor_id= ".(int)$VisitorId;
		return $this->_db->fetchRow($sql);
	}
	public function getListVisitor_nb($filter = array()) {
		$sqlPlus = $this->getListVisitor_sqlPlus($filter);
		$sql = "SELECT COUNT(visitor_id)
				FROM dw_visitors
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListVisitor($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListVisitor_sqlPlus($filter);
		$sql = "SELECT *
				FROM dw_visitors 
				WHERE 1=1 $sqlPlus ORDER BY name ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListVisitor_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND ( name LIKE '%$keyword%' OR visitor_id LIKE visitor_id$keyword%' OR email LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR code LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	
}

?>
