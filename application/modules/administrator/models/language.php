<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_language extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "language";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	function saveLangs($ids,$values){
		//print_r($ids);
		//print_r($values); die();
		for($i=0;$i<sizeof($ids);$i++){
			$id 	= $ids[$i];
			$value 	= $values[$i];
			$this->update(array('lang_value'=>$value), 'lang_id='.(int)$id);
		}
	}
	
	public function buldLangFile($fileName = 'en.ini'){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$langData	= null;
		$sql 		= "SELECT lang_key,lang_value FROM language";
		$langList   = $this->_db->fetchAll($sql);
		foreach((array)$langList as $item){
			$langData .= $item['lang_key'].'="'.$item['lang_value'].'";'."\r\n";
		}
		$filePath = ROOT_PATH.'/'.LANG_PATH.'/'.DEFAULT_LANGUAGE.'/'.$fileName;
		$objUtil->overWriteFile($filePath, $langData);
	}
	
	public function addData($arrLang,$file='fr.ini'){
		foreach((array)$arrLang as $item){
			@list($key,$val) = @explode('="', $item);
			$key = trim(strtolower($key));
			if($key != null && !$this->_checkExistsKey($key)){
				$val = trim(str_replace('";', '', $val));
				$this->insert(array('file_name'=>$file,'lang_key'=>$key,'lang_value'=>$val));
			}
		}
	}
	
	public function importOneFile($file=null){
		if(!$file) $file = 'en.ini';
		$filePath = ROOT_PATH.'/'.LANG_PATH.'/'.DEFAULT_LANGUAGE.'/'.$file;
		if(is_file($filePath)){
			$content  = file_get_contents($filePath);
			$arrLang = explode("\n", $content);
			foreach((array)$arrLang as $item){
				@list($key,$val) = @explode('="', $item);
				$key = trim(strtolower($key));
				if($key != null && !$this->_checkExistsKey($key)){
					$val = trim(str_replace('";', '', $val));
					$this->insert(array('file_name'=>$file,'lang_key'=>$key,'lang_value'=>$val));
				}
			}
		}
	}
		
	private function _checkExistsKey($key){
		$objUtil 	= new HT_Model_administrator_models_utility();
		$key 		= addslashes(strtolower($key));
		$sql 		= "SELECT lang_id FROM language WHERE lang_key = ? LIMIT 1";
		return $this->_db->fetchOne($sql,array($key));
	}
	
	public function getMaxId(){
		$sql = "SELECT MAX(lang_id) FROM language";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getLanguage($languageId,$filter = array()) {
		$sql = " SELECT * FROM language WHERE lang_id= ".(int)$languageId;
		return $this->_db->fetchRow($sql);
	}
	public function getListLanguage_nb($filter = array()) {
		$sqlPlus = $this->getListLanguage_sqlPlus($filter);
		$sql = "SELECT COUNT(lang_id)
				FROM language 
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	
	public function getListLanguage($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListLanguage_sqlPlus($filter);
		$sql = "SELECT *
				FROM language 
				WHERE 1=1 $sqlPlus ORDER BY lang_id DESC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListLanguage_sqlPlus($filter){
		$sqlPlus = null;
		foreach((array)$filter as $key => $val){
			$key = trim($key);
			$val = addslashes(trim($val));
			switch($key){
				case 'file_id':
					if($val){
						$sqlPlus .= " AND language.file_id = ".(int)$val;
					}
					break;
				case 'keyword':
					if($val){
						$sqlPlus .= " AND (language.lang_value LIKE '%$val%' ) "; //OR language.description LIKE '%$val%'
					}
					break;
			}
		}
		return $sqlPlus;
	}
	public function getValueKeyLanguage($querry) {
		return $this->_db->fetchOne($querry);
	}
}

?>
