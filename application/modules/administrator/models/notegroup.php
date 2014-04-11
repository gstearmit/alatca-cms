<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_notegroup extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "note_group";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$groupName = $data['group_name'];
		if(!$this->_checkExistsName($groupName)){
			$this->insert($data);
			return $this->getMaxId();
		}else{
			return "-1";
		}
	}
	
	public function updateData($data,$notegroupId){
		$groupName = $data['group_name'];
		if(!$this->_checkExistsName($groupName,$notegroupId)){
			$this->update($data,'group_id = '.(int)$notegroupId);
			return $notegroupId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsName($key,$notegroupId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		if($notegroupId >0){
			$sql 		= "SELECT COUNT(group_id) FROM note_group WHERE group_name = ? AND group_id <> ?";
			return $this->_db->fetchOne($sql,array($key,$notegroupId));
		}else{
			$sql 		= "SELECT COUNT(group_id) FROM note_group WHERE group_name = ?";
			return $this->_db->fetchOne($sql,array($key));
		}
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(group_id) FROM note_group";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getNotegroup($notegroupId,$filter = array()) {
		$sql = " SELECT * FROM note_group WHERE group_id= ".(int)$notegroupId;
		return $this->_db->fetchRow($sql);
	}
	public function getListNotegroup_nb($filter = array()) {
		$sqlPlus = $this->getListNotegroup_sqlPlus($filter);
		$sql = "SELECT COUNT(ngr.group_id)
				FROM note_group ngr
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListNotegroup($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListNotegroup_sqlPlus($filter);
		$sql = "SELECT ngr.*
				FROM note_group ngr
				WHERE 1=1 $sqlPlus ORDER BY ngr.group_order DESC,ngr.group_name ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListNotegroup_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		$keyword = addslashes($keyword);
		if($keyword){
			$sqlPlus .= " AND (ngr.group_name LIKE '%$keyword%' OR ngr.description LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	public function getValueKeyNotegroup($querry) {
		return $this->_db->fetchOne($querry);
	}
	
}

?>
