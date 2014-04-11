<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_page extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "pages";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function getPageByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT page_value FROM dw_pages WHERE title = ? ORDER BY page_id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	
	public function addData($data){
		$pageKey = $data['title'];
		if(!$this->_checkExistsKey($pageKey)){
			$this->insert($data);
			return $this->getMaxId();
		}else{
			return "-1";
		}
	}
	
	public function updateData($data,$pageId){
		$pageKey = $data['title'];
		if(!$this->_checkExistsKey($pageKey,$pageId)){
			$this->update($data,'page_id = '.(int)$pageId);
			return $pageId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$pageId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($pageId >0){
			$sql 		= "SELECT COUNT('page_id') FROM dw_pages WHERE title REGEXP BINARY '$key' AND page_id <> ".(int)$pageId;
		}else{
			$sql 		= "SELECT COUNT('page_id') FROM dw_pages WHERE title REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(page_id) FROM dw_pages";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getPage($pageId,$filter = array()) {
		$sql = " SELECT * FROM dw_pages WHERE page_id = ? LIMIT 1";
		return $this->_db->fetchRow($sql,$pageId);
	}
	public function getListPage_nb($filter = array()) {
		$sqlPlus = $this->getListPage_sqlPlus($filter);
		$sql = "SELECT COUNT(pg.page_id)
				FROM dw_pages pg
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListPage($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListPage_sqlPlus($filter);
		$sql = "SELECT pg.*
				FROM dw_pages pg
				WHERE 1=1 $sqlPlus ORDER BY pg.title ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListPage_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND (pg.title LIKE '%$keyword%' 
							   OR pg.domain LIKE '%$keyword%'
							   OR pg.url LIKE '%$keyword%'
							   OR pg.tags LIKE '%$keyword%'
						) ";
		}
		return $sqlPlus;
	}
	public function getValueKeyPage($querry) {
		return $this->_db->fetchOne($querry);
	}
	
}

?>
