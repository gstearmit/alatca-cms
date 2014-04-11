<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_session extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "sessions";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function getSessionByKey($key){
		$key 		= addslashes(strtolower(trim($key)));
		$sql 		= "SELECT session_value FROM sessions WHERE session_key = ? ORDER BY session_id LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	
	public function addData($data){
		$sessionKey = $data['session_key'];
		if(!$this->_checkExistsKey($sessionKey)){
			$this->insert($data);
			return $this->getMaxId();
		}else{
			return "-1";
		}
	}
	
	public function updateData($data,$sessionId){
		$sessionKey = $data['session_key'];
		if(!$this->_checkExistsKey($sessionKey,$sessionId)){
			$this->update($data,'session_id = '.(int)$sessionId);
			return $sessionId;
		}else{
			return "-1";
		}
	}

	private function _checkExistsKey($key,$sessionId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($sessionId >0){
			$sql 		= "SELECT COUNT('session_id') FROM sessions WHERE session_key REGEXP BINARY '$key' AND session_id <> ".(int)$sessionId;
		}else{
			$sql 		= "SELECT COUNT('session_id') FROM sessions WHERE session_key REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(session_id) FROM sessions";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getSession($sessionId,$filter = array()) {
		$sql = " SELECT * FROM sessions WHERE session_id= ".(int)$sessionId;
		return $this->_db->fetchRow($sql);
	}
	
	public function getCTAList() {
		$returnList = array();
		$sql = " SELECT page_url FROM tracking_cta_page WHERE 1=1";
		$urlList = $this->_db->fetchAll($sql);
		foreach((array)$urlList as $url){
			$returnList[] = $url;
		}
		return $returnList;
	}

	public function getListSession($filter = array()){
		$sql = "SELECT tk.session_id
				FROM tracking_tracking tk
				WHERE 1=1 GROUP BY tk.session_id";
		$sessionList = $this->_db->fetchAll($sql);
		$this->_countCTA($sessionList);
		$this->_getSource($sessionList);
		return $sessionList;
	}
	
	public function _getSource(&$sessionList){
		for($i=0;$i<sizeof($sessionList);$i++){
			$session_id = $sessionList[$i]['session_id'];
			
			$sql = "SELECT src.url source
				FROM tracking_tracking tk
				INNER JOIN tracking_url src ON src.id = tk.id_url_refer
				WHERE session_id = ? LIMIT 1";
			$sessionList[$i]['source'] = $this->_db->fetchOne($sql,array($session_id));
		}
	}
	
	public function _getDestinationList($session_id){
		$sql = "SELECT des.url destination
				FROM tracking_tracking tk
				INNER JOIN tracking_url des ON des.id = tk.id_url
				WHERE session_id = ? ";
		$this->_db->fetchAll($sql,array($session_id));
	}
	
	private function _countCTA(&$sessionList){
		$ctaUrl = $this->getCTAList();
		for($i=0;$i<sizeof($sessionList);$i++){
			$session_id = $sessionList[$i]['session_id'];
			$destinationList = $this->_getDestinationList($session_id);
			$hasCTA = 0;
			foreach((array)$destinationList as $des){
				$destination = $des['destination'];
				if(in_array($destination,$ctaUrl)){
					$hasCTA = 1;
				}
			}
			$sessionList[$i]['cta'] = $hasCTA;
		}
	}
}

?>
