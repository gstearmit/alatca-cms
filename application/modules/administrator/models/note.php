<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_note extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "notes";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function getNoteByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT note_title FROM notes WHERE note_key = ? ORDER BY note_id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	
	public function addData($data){
		$noteKey = $data['note_key'];
		if(!$this->_checkExistsKey($noteKey)){
			$this->insert($data);
			return $this->getMaxId();
		}else{
			return "-1";
		}
	}
	
	public function updateData($data,$noteId){
		$noteKey = $data['note_key'];
		if(!$this->_checkExistsKey($noteKey,$noteId)){
			$this->update($data,'note_id = '.(int)$noteId);
			return $noteId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$noteId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($noteId >0){
			$sql 		= "SELECT note_id FROM notes WHERE note_key =? AND note_id <> ? LIMIT 1";
			return $this->_db->fetchOne($sql,array($key,$noteId));
		}else{
			$sql 		= "SELECT note_id FROM notes WHERE note_key = ? LIMIT 1";
			return $this->_db->fetchOne($sql,array($key));
		}
		
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(note_id) FROM notes";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getNote($noteId,$filter = array()) {
		$sql = " SELECT * FROM notes WHERE note_id= ".(int)$noteId;
		return $this->_db->fetchRow($sql);
	}
	public function getListNote_nb($filter = array()) {
		$sqlPlus = $this->getListNote_sqlPlus($filter);
		$sql = "SELECT COUNT(notes.note_id)
				FROM notes 
				INNER JOIN note_group  ON note_group.group_id = notes.group_id
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	
	public function getHomeNotes(){
		$sql = "SELECT note_key,note_title title,description
				FROM notes ";
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getProductNotes($group_id){
		$sql = "SELECT note_key,note_title title,description
				FROM notes
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getPrixNotes($group_id){
		$sql = "SELECT note_key,note_title title,description
				FROM notes
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getsmartphoneNotes($group_id){
		$sql = "SELECT note_key,note_title title,description
				FROM notes
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getbackofficeNotes($group_id){
		$sql = "SELECT note_key,note_title title,description
				FROM notes
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function gettabletteNotes($group_id){
		$sql = "SELECT note_key,note_title title,description
				FROM notes
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getmarketingNotes($group_id){
		$sql = "SELECT note_key,note_title title,description
				FROM notes
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getarchiveNotes($group_id){
		$sql = "SELECT note_key,note_title title,description
				FROM notes
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function gettelechargerNotes($group_id){
		$sql = "SELECT note_key,note_title title,description
				FROM notes
				Where group_id =".$group_id;
		return $this->_db->fetchAssoc($sql);
	}
	
	public function getListNote($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListNote_sqlPlus($filter);
		$sql = "SELECT notes.*
				FROM notes 
				INNER JOIN note_group  ON note_group.group_id = notes.group_id
				WHERE 1=1 $sqlPlus ORDER BY notes.note_key ASC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListNote_sqlPlus($filter){
		$sqlPlus = null;
		foreach((array)$filter as $key => $val){
			$key = trim($key);
			$val = addslashes(trim($val));
			switch($key){
				case 'group_id':
					if($val){
						$sqlPlus .= " AND notes.group_id = ".(int)$val;
					}
					break;
				case 'keyword':
					if($val){
						$sqlPlus .= " AND (notes.note_title LIKE '%$val%' ) "; //OR notes.description LIKE '%$val%'
					}
					break;
			}
		}
		return $sqlPlus;
	}
	public function getValueKeyNote($querry) {
		return $this->_db->fetchOne($querry);
	}
}

?>
