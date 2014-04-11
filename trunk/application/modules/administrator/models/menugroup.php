<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_menugroup extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "zend_menugroup";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(group_id) FROM zend_menugroup";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getMenugroup($typeId,$filter = array()) {
		$sql = " SELECT * FROM zend_menugroup WHERE group_id= ".(int)$typeId;
		return $this->_db->fetchRow($sql);
	}
	public function getListMenugroup_nb($filter = array()) {
		$sqlPlus = $this->getListMenugroup_sqlPlus($filter);
		$sql = "SELECT COUNT(group_id)
		FROM zend_menugroup
		WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListMenugroup($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListMenugroup_sqlPlus($filter);
		$sql = "SELECT *
		FROM zend_menugroup
		WHERE 1=1 $sqlPlus ORDER BY group_order DESC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListMenugroup_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		if($keyword){
			$sqlPlus .= " AND (group_name LIKE '%$keyword%' OR `description` LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
    public function getAll($where){
        $sql = "select * from zend_menugroup where ".$where;
        $retval = $this->_db->fetchAll($sql);
        return $retval;
    }
    
    public function findById($id){
        $sql = "select * from zend_menugroup where group_id = $id";
        $retval = $this->_db->fetchRow($sql);
        return $retval;
    }
	
}

?>
