<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_slider extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "slider";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function getsliderByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT sil_title FROM slider WHERE sil_key = ? ORDER BY sil_id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	
	public function addData($data){
		$sliderKey = $data['sil_key'];
		if(!$this->_checkExistsKey($sliderKey)){
			$this->insert($data);
			return $this->getMaxId();
		}else{
			return "-1";
		}
	}
	
	public function getAllslider(){
		$sql = "SELECT sil_id, sil_key ,sil_title title,sil_description,bg_article_img,
                sil_img_01,sil_img_02,sil_img_03,sil_img_04,link_news
                FROM slider ";
		return $this->_db->fetchAssoc($sql);
	}
	
	public function updateData($data,$sliderId){
		$sliderKey = $data['sil_key'];
		if(!$this->_checkExistsKey($sliderKey,$sliderId)){
			$this->update($data,'sil_id = '.(int)$sliderId);
			return $sliderId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$sliderId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($sliderId >0){
			$sql 		= "SELECT sil_id FROM slider WHERE sil_key REGEXP BINARY '$key' AND sil_id <> ".(int)$sliderId;
		}else{
			$sql 		= "SELECT sil_id FROM slider WHERE sil_key REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(sil_id) FROM slider";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getslider($sliderId,$filter = array()) {
		$sql = " SELECT * FROM slider WHERE sil_id= ".(int)$sliderId;
		return $this->_db->fetchRow($sql);
	}
	public function getListslider_nb($filter = array()) {
		$sqlPlus = $this->getListslider_sqlPlus($filter);
		$sql = "SELECT COUNT(slider.sil_id)
				FROM slider
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	
	public function getHomeslider(){
		$sql = "SELECT sil_key,sil_title title,sil_description,sil_description,bg_article_img,
                sil_img_01,sil_img_02,sil_img_03,sil_img_04,link_news
				FROM slider ";
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getProductslider($group_id){
		$sql = "SELECT sil_key,sil_title title,description
				FROM slider
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getPrixslider($group_id){
		$sql = "SELECT sil_key,sil_title title,description
				FROM slider
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getsmartphoneslider($group_id){
		$sql = "SELECT sil_key,sil_title title,description
				FROM slider
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getListslider($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListslider_sqlPlus($filter);
		$sql = "SELECT slider.*
				FROM slider 
				WHERE 1=1 $sqlPlus ORDER BY slider.sil_key ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListslider_sqlPlus($filter){
		$sqlPlus = null;
		foreach((array)$filter as $key => $val){
			$key = trim($key);
			$val = addslashes(trim($val));
			switch($key){
				case 'keyword':
					if($val){
						$sqlPlus .= " AND (slider.sil_title LIKE '%$val%' ) "; //OR slider.description LIKE '%$val%'
					}
					break;
			}
		}
		return $sqlPlus;
	}
	public function getValueKeyslider($querry) {
		return $this->_db->fetchOne($querry);
	}
}

?>
