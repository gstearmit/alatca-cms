<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_menu extends Zend_Db_Table {//ten class fai viet hoa

	protected $_db;

	public function __construct() {
		$this->_name = "zend_menu";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	private function checkExistsUrl($menu_url,$type='insert',$menu_id = null){
		if($type == 'insert'){
			$sql = "SELECT COUNT(menu_id) FROM zend_menu WHERE menu_url = ?";
			return  (int)$this->_db->fetchOne($sql,array($menu_url));
		}else{
			$sql = "SELECT COUNT(menu_id) FROM zend_menu WHERE menu_url = ? AND menu_id <> ?";
			return  (int)$this->_db->fetchOne($sql,array($menu_url,$menu_id));
		}
	}
	
	public function addData($data){
		$menu_url 			= trim($data['menu_url']);
		if(!$this->checkExistsUrl($menu_url)){
			$actionId = $this->getActionId($menu_url);
			if($actionId >0){
				$data['action_id'] = $actionId;
				$this->insert($data);
				return $this->getMaxId();
			}else{
				return "-2";
			}
		}else{
			return "-1";
		}
	}
	
	public function updateData($data,$menu_id){
		$menu_url 			= trim($data['menu_url']);
		if(!$this->checkExistsUrl($menu_url,'update',$menu_id)){
			$actionId = $this->getActionId($menu_url);
			if($actionId >0){
				$data['action_id'] = $actionId;
				$this->update($data, "menu_id=".(int)$menu_id);
				return $menu_id;
			}else{
				return "-1";
			}
		}else{
			return "-1";
		}
	}
	
	private function getActionId($menu_url){
		@list($info,$data) = @explode('administrator/', $menu_url);
		$data = trim($data);
		$actionId = 0;
		if($data){
			$arr = explode('/', $data);
			$moduleName = trim(@$arr[0]);
			if(strpos($moduleName,'?')!== false){
				list($moduleName,$other) = explode('?', $moduleName);
			}
			$actionName = trim(@$arr[1]);
			if(!$actionName){
				$actionName = "index";
			}elseif(strpos($actionName,'?')!== false){
				list($actionName,$other) = explode('?', $actionName);
			}
			
			if($moduleName && $actionName){
				$sql = "SELECT action_id 
						FROM zend_actions ac
						INNER JOIN zend_modules md ON ac.module_id = md.module_id
						WHERE md.module_name = ? AND action_name = ?";
				$actionId = (int)$this->_db->fetchOne($sql,array($moduleName,$actionName));
			}
		}
		return $actionId;
	}
	
	private function getModuleId($menu_url){
		@list($info,$data) = @explode('administrator/', $menu_url);
		$data = trim($data);
		$moduleId = 0;
		if($data){
			$arr = explode('/', $data);
			$moduleName = trim($arr[0]);
			if($moduleName){
				$sql = "SELECT module_id FROM zend_modules WHERE module_name = ?";
				$moduleId = (int)$this->_db->fetchOne($sql,array($moduleName));
			}
		}
		return $moduleId;
	}
		
	public function getMaxId(){
		$sql = "SELECT MAX(menu_id) FROM zend_menu";
		return  (int)$this->_db->fetchOne($sql);
	}
	public function getMenu($typeId,$filter = array()) {
		$sql = " SELECT * FROM zend_menu WHERE menu_id= ".(int)$typeId;
		return $this->_db->fetchRow($sql);
	}
	
	public function buildLeftMenu($groupMenu,$actionRight){
		$groupList  = array();
		foreach((array)$groupMenu as $group){
			$i = 0;
			$menuList 		= $group['menu_list'];
			$groupUrl 		= $group['group_url'];
			foreach($menuList as $menu){
				$actionId = $menu['action_id'];
				if(in_array($actionId,$actionRight)){
					$i++;
				}
			}
			$group['has_sub'] = 0;
			if($i >0){
				$group['has_sub'] = 1;
				$groupList[] = $group;
			}elseif($groupUrl){
				$actionId = $this->getActionId($groupUrl);
				if(in_array($actionId,$actionRight)){
					$groupList[] = $group;
				}
			} 
		}
		return $groupList;
	}
	
	public function getLeftMenu(){
		$leftMenu = $this->getGroupMenu();
		for($i=0;$i<sizeof($leftMenu);$i++){
			$leftMenu[$i]['menu_list'] = $this->getMenuByGroup((int)$leftMenu[$i]['group_id']);
		}
		return $leftMenu;
	}
	
	public function getMenuByGroup($groupId){
		$sql = " SELECT * FROM zend_menu WHERE group_id = ? ORDER BY menu_order DESC";
		return $this->_db->fetchAll($sql,array($groupId));
	}
	
	public function getGroupMenu(){
		$sql = " SELECT group_id,group_name,group_url,group_icon FROM zend_menugroup ORDER BY group_order DESC";
		return $this->_db->fetchAll($sql);
	}
	
	public function getListMenu_nb($filter = array()) {
		$sqlPlus = $this->getListMenu_sqlPlus($filter);
		$sql = "SELECT COUNT(mn.menu_id)
				FROM zend_menu mn
				INNER JOIN zend_menugroup grp ON grp.group_id = mn.group_id
				INNER JOIN zend_actions ac ON ac.action_id = mn.action_id
				INNER JOIN zend_modules md ON md.module_id = ac.module_id				
				WHERE 1=1 $sqlPlus";
		return $this->_db->fetchOne($sql);
	}
	public function getListMenu($start=0,$size = 10,$filter = array()) {
		$sqlPlus = $this->getListMenu_sqlPlus($filter);
		$sql = "SELECT mn.*, md.module_name, ac.action_name,grp.group_name
				FROM zend_menu mn
				INNER JOIN zend_menugroup grp ON grp.group_id = mn.group_id
				INNER JOIN zend_actions ac ON ac.action_id = mn.action_id
				INNER JOIN zend_modules md ON md.module_id = ac.module_id				
				WHERE 1=1 $sqlPlus ORDER BY grp.group_id ASC, mn.menu_order DESC LIMIT $start,$size";
		return $this->_db->fetchAll($sql);
	}
	
	private function getListMenu_sqlPlus($filter){
		$sqlPlus = null;
		$keyword = trim(@$filter['keyword']);
		if($keyword){
			$sqlPlus .= " AND (mn.menu_name LIKE '%$keyword%' OR mn.menu_description LIKE '%$keyword%' OR md.module_name LIKE '%$keyword%' OR ac.action_name LIKE '%$keyword%') ";
		}
		return $sqlPlus;
	}
    public function getAll($where){
        $sql = "select * from zend_menu where ".$where;
        $retval = $this->_db->fetchAll($sql);
        return $retval;
    }
    
    public function findById($id){
        $sql = "select * from zend_menu where menu_id = $id";
        $retval = $this->_db->fetchRow($sql);
        return $retval;
    }
	
}

?>
