
<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_domain extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "tracking_domain";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(domain_id) FROM tracking_domain";
		return  (int)$this->_db->fetchOne($sql);
	}
	
	public function getListall($query) {
		$rows= array();
		$rows= $this->_db->fetchAll($query);
		return $rows;
	}
	
	public function getdomainByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT domain FROM tracking_domain WHERE domain_name = ? ORDER BY domain_id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	
	
	public function updateData($data,$domainId){
		$domainKey = $data['domain_name'];
		if(!$this->_checkExistsKey($domainKey,$domainId)){
			$this->update($data,'domain_id = '.(int)$domainId);
			return $domainId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$domainId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($domainId >0){
			$sql 		= "SELECT COUNT('domain_id') FROM tracking_domain  WHERE domain_name REGEXP BINARY '$key' AND domain_id <> ".(int)$domainId;
		}else{
			$sql 		= "SELECT COUNT('domain_id') FROM tracking_domain  WHERE domain_name REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	
	
	public function getdomain($domainId,$filter = array()) {
		$sql = " SELECT * FROM tracking_domain WHERE domain_id= ".(int)$domainId;
		return $this->_db->fetchRow($sql);
	}
	public function getListdomain_nb($filter = array()) {
		$sqlPlus = $this->getListdomain_sqlPlus($filter);
		$sql = "SELECT COUNT(domain_id)
				FROM tracking_domain
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListdomain($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListdomain_sqlPlus($filter);
		$sql = "SELECT *
				FROM tracking_domain 
				WHERE 1=1 $sqlPlus ORDER BY domain_name ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListdomain_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND ( domain_name LIKE '%$keyword%' OR domain LIKE '%$keyword%' OR description LIKE '%$keyword%' ) ";
		}
		return $sqlPlus;
	}
	
}

?>
