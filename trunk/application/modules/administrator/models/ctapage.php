<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_ctapage extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "tracking_cta_page";
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
	
	public function updateData($data,$ctapageId){
		$pageUrl = $data['page_url'];
		if(!$this->_checkExistsUrl($pageUrl,$ctapageId)){
			$this->update($data,'page_id = '.(int)$ctapageId);
			return $ctapageId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsUrl($pageUrl,$ctapageId = null){
		$objUtil 		= new HT_Model_administrator_models_utility();
		$pageUrl 		= addslashes(strtolower(trim($pageUrl)));
		if($ctapageId >0){
			$sql 		= "SELECT COUNT(page_id) FROM tracking_cta_page WHERE page_url REGEXP BINARY '$pageUrl' AND page_id <> ".(int)$ctapageId;
		}else{
			$sql 		= "SELECT COUNT(page_id) FROM tracking_cta_page WHERE page_url REGEXP BINARY '$pageUrl'";
		}
		
		//echo $sql; die();
		return $this->_db->fetchOne($sql);
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(page_id) FROM tracking_cta_page";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getCtapage($ctapageId,$filter = array()) {
		$sql = " SELECT * FROM tracking_cta_page WHERE page_id= ".(int)$ctapageId;
		return $this->_db->fetchRow($sql);
	}
	public function getListCtapage_nb($filter = array()) {
		$sqlPlus = $this->getListCtapage_sqlPlus($filter);
		$sql = "SELECT COUNT(sup.page_id)
				FROM tracking_cta_page sup
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListCtapage($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListCtapage_sqlPlus($filter);
		$sql = "SELECT sup.*
				FROM tracking_cta_page sup
				WHERE 1=1 $sqlPlus ORDER BY sup.page_name ASC LIMIT $start,$size";
		$listCTA = $this->_db->fetchAll($sql);
		for($i=0;$i<sizeof($listCTA);$i++){
			$pageUrl 				= $listCTA[$i]['page_url'];
			$successUrl 			= $listCTA[$i]['success_url'];
			$listCTA[$i]['click'] 	= $this->_countCTA($pageUrl,$successUrl);
		}
		return $listCTA;
	}
	
	private function _countCTA($pageUrl,$successUrl){
		$sql = "SELECT COUNT(ck.id) 
				FROM tracking_tracking ck
				INNER JOIN tracking_url rf_url ON rf_url.id = ck.id_url_refer AND rf_url.url = ?
				INNER JOIN tracking_url ss_url ON ss_url.id = ck.id_url AND ss_url.url = ?
				WHERE 1=1";
		return $this->_db->fetchOne($sql,array($pageUrl,$successUrl));
	}
	
	private function getListCtapage_sqlPlus($filter){
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
