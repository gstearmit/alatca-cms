<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class HT_Model_administrator_models_retracking extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;
	
	public function __construct() {
		$this->_name = "tracking_retracking";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function addData($data){
		$this->insert($data);
		return $this->getMaxId();
	
		}

     public function getMaxId(){
		$sql = "SELECT MAX(id) FROM tracking_retracking";
		return  (int)$this->_db->fetchOne($sql);
	}
	
 
    
}

?>
