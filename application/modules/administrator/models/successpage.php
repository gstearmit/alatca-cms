<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_successpage extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "tracking_success_page";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$pageUrl = $data['page_url'];
		if(!$this->_checkExistsUrl($pageUrl)){
			$this->insert($data);
			return $this->getMaxId();
		}else{
			return "-1";
		}
	}
	
	public function updateData($data,$successpageId){
		$pageUrl = $data['page_url'];
		if(!$this->_checkExistsUrl($pageUrl,$successpageId)){
			$this->update($data,'page_id = '.(int)$successpageId);
			return $successpageId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsUrl($pageUrl,$successpageId = null){
		$objUtil 		= new HT_Model_administrator_models_utility();
		$pageUrl 		= addslashes(strtolower(trim($pageUrl)));
		if($successpageId >0){
			$sql 		= "SELECT COUNT(page_id) FROM tracking_success_page WHERE page_url REGEXP BINARY '$pageUrl' AND page_id <> ".(int)$successpageId;
		}else{
			$sql 		= "SELECT COUNT(page_id) FROM tracking_success_page WHERE page_url REGEXP BINARY '$pageUrl'";
		}
		
		//echo $sql; die();
		return $this->_db->fetchOne($sql);
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(page_id) FROM tracking_success_page";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getSuccesspage($successpageId,$filter = array()) {
		$sql = " SELECT * FROM tracking_success_page WHERE page_id= ".(int)$successpageId;
		return $this->_db->fetchRow($sql);
	}
	public function getListSuccesspage_nb($filter = array()) {
		$sqlPlus = $this->getListSuccesspage_sqlPlus($filter);
		$sql = "SELECT COUNT(sup.page_id)
				FROM tracking_success_page sup
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListSuccesspage($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListSuccesspage_sqlPlus($filter);
		$sql = "SELECT sup.*
				FROM tracking_success_page sup
				WHERE 1=1 $sqlPlus ORDER BY sup.page_name ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListSuccesspage_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND (sup.page_name LIKE '%$keyword%' OR sup.page_url LIKE '%$keyword%' OR sup.description LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	
}

?>
