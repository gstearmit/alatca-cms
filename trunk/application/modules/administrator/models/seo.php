<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_seo extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;
	protected $_name;
	protected $_seo_header_profile;

	public function __construct() {
		$this->_name = "seo_header";
		$this->_seo_header_profile = 'seo_header_profile';
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
		
	}
	public function getseo_header_id($seo_headerid,$filter = array()) {
		$sql = " SELECT * FROM seo_header WHERE header_id= ".(int)$seo_headerid;
		return $this->_db->fetchRow($sql);
	}
	public function changepassAction(){
		
	}
	
	public function updateData($data,$header_id){
		$configKey = $data['config_key'];
		if($header_id!=null){
			$this->update($data,'header_id = '.$header_id);
			return "1";
		}else{
			return "-1";
		}
	}
	private function _checkExistsKey($key,$configId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		if($configId >0){
			$sql 		= "SELECT COUNT('header_id') FROM seo_header WHERE header_id REGEXP BINARY '$key' AND config_id <> ".(int)$configId;
		}else{
			$sql 		= "SELECT COUNT('header_id') FROM seo_header WHERE header_id REGEXP BINARY '$key'";
		}
		return $this->_db->fetchOne($sql);
	}
	
	public function login($seo_headername,$password){
		$seo_headername		= trim($seo_headername);
		$password		= md5($password);
		$seo_header 			= $this->_getseo_headerInfoByseo_headername($seo_headername);
		$seo_headerid 		= $seo_header['seo_headerid'];
		$pass 			= trim($seo_header['pass']);
		if($pass === $password){
			return $seo_header;
		}else{
			return false;
		}
	}
	
	private function _getseo_headerInfoByseo_headername($seo_headername){
		$sql = " SELECT us.*,role.role_name 
				 FROM seo_header us
				 INNER JOIN seo_header_roles role ON us.role_id = role.role_id
				 WHERE us.seo_header_name = ? AND us.active = 1 LIMIT 1";
		return $this->_db->fetchRow($sql,array($seo_headername));
	}
	
	public function getRandomseo_headers($limit){
		$ids = $this->getseo_headerIds($limit);
		$sql = " SELECT * FROM seo_header WHERE seo_headerid IN (".implode(',', $ids).")";
		return $this->_db->fetchAll($sql);
	}
	
	private function getseo_headerIds($limit){
		$ids = array();
		$commentIds = array();
		$sql = " SELECT seo_headerid FROM seo_header WHERE active = 1 LIMIT 1000,3000";
		$idList = $this->_db->fetchAll($sql);
		foreach((array)$idList as $idData){
			$ids[] = $idData['seo_headerid'];
		}
		
		while(sizeof($commentIds) < $limit){
			$index = rand(0,sizeof($ids)-1);
			if(!in_array($index,$commentIds)){
				$commentIds[] = $index;
			}
		}
		return $commentIds;
	}
	
	public function getAll($where){
		$sql = "select * from seo_header where ".$where;
		$retval = $this->_db->fetchAll($sql);
		return $retval;
	}
	public function findByseo_headerName($seo_header_name){
		$sql = "select * from seo_header where seo_header_name = '$seo_header_name' and active = 0";
		$retval = $this->_db->fetchRow($sql);
		return $retval;
	}
	public function findById($id){
		$sql = "select Ur.*, Up.* from seo_header Ur left join seo_header_profile Up on Ur.seo_header_name = Up.seo_header_name where seo_headerid = '$id'";
		$retval = $this->_db->fetchRow($sql);
		return $retval;
	}
	function deleteseo_header($seo_headerId){
		$where = $this->_db->quoteInto("header_id = ?",$seo_headerId);
		return $this->delete($where);
	}	
	function addData($data){
		$this->insert($data);
		//return $this->getMaxId();
		return 2;
	}
	
	public function getKeyword(){
		$front 			= Zend_Controller_Front::getInstance();
		$request	 	= $front->getRequest();
		$module 		= $request->getModuleName();
		$controller 	= $request->getControllerName();
		$action 		= $request->getActionName();
		
		$key =  $module.'.'.$controller.'.'.$action;
		
		$sql = "SELECT * FROM seo_header WHERE header_key = ? LIMIT 1";
		return  $this->_db->fetchRow($sql,array($key));
	}
	
	public function checkExistsseo_headername($seo_headername,$seo_headerId = null){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$seo_headername 		= addslashes(strtolower($seo_headername));
		if($seo_headerId >0){
			$sql 		= "SELECT COUNT(seo_headerid) FROM seo_header WHERE seo_header_name = ? AND seo_header_id <>  ?";
			return $this->_db->fetchOne($sql,array($seo_headername,$seo_headerId));
		}else{
			$sql 		= "SELECT COUNT(seo_headerid) FROM seo_header WHERE seo_header_name = ?";
			return $this->_db->fetchOne($sql,array($seo_headername));
		}
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(seo_header_name) FROM seo_header";
		return  (int)$this->_db->fetchOne($sql);
	}
	
	public function getseo_headerById($seo_headerid,$filter = array()){
		$sqlPlus = $this->_getseo_header_sqlPlus($filter);
		$sqlJoin	= $this->_getseo_header_sqlJoin($filter);
		$sql = " SELECT us.*, CONCAT(us.header_key,' ',us.tag_title) tag_description $sqlPlus
		FROM seo_header us $sqlJoin
		WHERE us.header_id = ? ";
		$seo_header 	= $this->_db->fetchRow($sql,array($seo_headerid));
		$this->_getseo_header_More($seo_header,$filter);
		$seo_header = $this->tooObject($seo_header);
		return $seo_header;
	}
	
	public function getseo_header($seo_headername,$filter = array()) {
		$sqlPlus = $this->_getseo_header_sqlPlus($filter);
		$sqlJoin	= $this->_getseo_header_sqlJoin($filter);
		$sql = " SELECT us.*, CONCAT(us.firstname,' ',us.lastname) fullname $sqlPlus
				FROM seo_header us $sqlJoin
				WHERE us.seo_header_name= ? ";
		$seo_header 	= $this->_db->fetchRow($sql,array($seo_headername));
		$this->_getseo_header_More($seo_header,$filter);
		$seo_header = $this->tooObject($seo_header);
		return $seo_header;
	}
	
	function tooObject($dataArray){
	    if(!is_array($dataArray)){return $dataArray;}
	    $dataObject = new stdClass;
	    foreach($dataArray as $key => $value){
	        $dataObject->$key = (is_array($value)) ? $this->tooObject($value) : $value;
	    }
	    return $dataObject;
	}
	
	private function _getseo_header_sqlPlus($filter = array()){
		$sqlPlus = null;
		foreach((array)$filter as $key => $val){
			switch($key){
				case 'get_blood_type':
					$sqlPlus .= ', blt.blood_type as blood_type_name';
				break;
			}
		}
		return $sqlPlus;
	}
	
	private function _getseo_header_sqlJoin($filter = array()){
		$sqlJoin = null;
		foreach((array)$filter as $key => $val){
			switch($key){
				case 'get_blood_type':
					$sqlJoin .= ' LEFT JOIN blood_type blt on us.blood_type = blt.id ';
					break;
			}
		}
		return $sqlJoin;
	}
	
	private function _getseo_header_More(&$seo_header,$filter = array()){
		
	}
	
	public function getListseo_header_nb($filter = array()) {
		$sqlPlus = $this->getListseo_header_sqlPlus($filter);
		$sql = "SELECT COUNT(us.header_id)
				FROM seo_header us
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListseo_header($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListseo_header_sqlPlus($filter);
		$sql = "SELECT us.*
				FROM seo_header us
				
				WHERE 1=1 $sqlPlus ORDER BY us.header_id LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListseo_header_sqlPlus($filter){
		$sqlPlus = null;
		if(isset($filter['keyword'])){
			$keyword = addslashes(trim(@$filter['keyword']));
			$sqlPlus .= " AND (us.header_key LIKE '%$keyword%' OR
								us.tag_title LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
	/**
	 * @description : update seo_header
	 * @param unknown $data
	 * @param unknown $seo_header_name
	 * @return Ambigous <boolean, number>
	 */
	function updateseo_headerprofile($data,$seo_header_name){
		//zf_debug($data); die();
		if (!empty($seo_header_name) && !is_array($data) ) {
			return false;
		}
		$update = false;
		$checkseo_header = $this->getseo_headerByName($seo_headername);
		if (is_array($checkseo_header)) {
			$update = $this->_db->update($this->_seo_header_profile, $data,$this->getAdapter()->quoteInto("seo_header_name = ?", $seo_header_name));
		}else{
			$data = array_merge($data,array('seo_header_name'=>$seo_header_name,'time'=>time()));
			//zf_debug($data); die();
			$this->_db->insert($this->_seo_header_profile, $data);	
		} 
		return $update;
	}
	/**
	 * @description : get seo_header by name
	 * @param unknown $seo_headername
	 */
	public function getseo_headerByName($seo_headername){
		$data_seo_header = false;
		$query = $this->_db->select()
				->from($this->_seo_header_profile,'*')
				->where($this->getAdapter()->quoteInto("seo_header_name = ?", $seo_headername));
		$data = $this->_db->fetchRow($query);
		
		if (is_array($data)) {
			$data_seo_header = $data;
		}
		return $data_seo_header;			
	}
	
	
	public function getseo_headerPassword($seo_headerId,$pass){
		$data_seo_header = false;
		
		$query="SELECT * FROM `seo_header` WHERE `seo_headerid` = '".$seo_headerId."' and `pass` = '".$pass."'";
		
		$data= $this->_db->fetchRow($query);
		
		return $data;
	}
}

?>
