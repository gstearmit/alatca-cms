<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_packageitem extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "package_item";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function getPackageitemByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT active FROM package_item WHERE item_name = ? ORDER BY item_id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	
	public function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	}
	
	public function updateData($data,$packageitemId){
		$this->update($data,'item_id = '.(int)$packageitemId);
		return $packageitemId;
	}

	private function _checkExistsKey($key,$packageitemId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($packageitemId >0){
			$sql 		= "SELECT item_id FROM package_item WHERE item_name REGEXP BINARY '$key' AND item_id <> ".(int)$packageitemId;
		}else{
			$sql 		= "SELECT item_id FROM package_item WHERE item_name REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(item_id) FROM package_item";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getPackageitem($packageitemId,$filter = array()) {
		$sql = " SELECT * FROM package_item WHERE item_id= ".(int)$packageitemId;
		return $this->_db->fetchRow($sql);
	}
	public function getListPackageitem_nb($filter = array()) {
		$sqlPlus = $this->getListPackageitem_sqlPlus($filter);
		$sql = "SELECT COUNT(pki.item_id)
				FROM package_item pki
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	
	public function getHomePackageitems(){
		$sql = "SELECT item_name,active title,description
				FROM package_item";
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getListPackageitem($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListPackageitem_sqlPlus($filter);
		$sql = "SELECT pki.*,pk.package_id, pk.package_name
				FROM package_item pki
				INNER JOIN package pk ON pk.package_id = pki.package_id
				WHERE 1=1 $sqlPlus ORDER BY pk.package_id ASC,pki.item_order DESC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListPackageitem_sqlPlus($filter){
		$sqlPlus = null;
		foreach((array)$filter as $key => $val){
			$key = trim($key);
			$val = addslashes(trim($val));
			switch($key){
				case 'package_id':
					if($val){
						$sqlPlus .= " AND pki.package_id =".(int)$val;
					}
					break;
				case 'keyword':
					if($val){
						$sqlPlus .= " AND (pki.item_name LIKE '%$val%') ";
					}
					break;
			}
		}
		return $sqlPlus;
	}
	public function getValueKeyPackageitem($querry) {
		return $this->_db->fetchOne($querry);
	}
}

?>
