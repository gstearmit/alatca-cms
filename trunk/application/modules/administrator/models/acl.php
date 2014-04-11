<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class HT_Model_administrator_models_acl extends Zend_Db_Table {//ten class fai viet hoa
	protected $_db;
	public function __construct() {
		$this->_name = "zend_rights";
		$this->_db = Zend_Registry::get('dbMain');
		parent::init();
	}
	
	public function autoUpdateModules($fileList = array()){
		$objModule = new HT_Model_administrator_models_module();
		$objAction = new HT_Model_administrator_models_action();
		$modules = array();
		foreach($fileList as $fileItem){
				$filePath			= $fileItem['filePath'];
				$fileName			= $fileItem['fileName'];
				$moduleNameDisplay 	= trim(substr($fileName, 0,-14));
				$moduleName			= strtolower($moduleNameDisplay);
				if($moduleName){
					$moduleId			= null;
					$data 				= array('module_name'=>$moduleName,'module_name_display'=>$moduleNameDisplay);
					$moduleId 			= $objModule->addData($data);
					if(!$moduleId) $moduleId = $objModule->getModuleIdByName($moduleName);
					// 
					$handle = fopen($filePath, "r");
					$contents = fread($handle, filesize($filePath));
					$arr = explode('function ', $contents);
					foreach((array)$arr as $item){
						$actionId = null;
						if(strpos($item, 'Action()')){
							list($actionName,$str) = explode('Action()', $item);
							$actionNameDisplay = trim($actionName);
							$actionName = strtolower($actionName);
							if($actionName && strlen($actionName) < 100){ 
								if($actionName == 'update') $actionNameDisplay = "Cập nhật";
								if($actionName == 'edit') $actionNameDisplay = "Sửa";
								if($actionName == 'add') $actionNameDisplay = "Thêm mới";
								if($actionName == 'delete') $actionNameDisplay = "Xóa";
								if($actionName == 'index') $actionNameDisplay = "Truy cập";
								$data = array('module_id'=>$moduleId,'action_name'=>$actionName,'action_name_display'=>$actionNameDisplay);
								$actionId = (int)$objAction->addData($data);
								if(!$actionId) $actionId = $objAction->getActionByName($actionName,$moduleId);
								if($actionId >0){
									// Supper Admin
									$roleIds = array(1);
									$this->setRightRoles($roleIds,$actionId);
								}
							}
						}
						
					}	
					fclose($handle);
			}
		}
		return true;
	}
	
	public function checkRightAccess($roleId,$module,$action){
		$sql = "SELECT zr.right_id
				FROM zend_rights zr
				INNER JOIN zend_actions ac ON ac.action_id = zr.action_id
				INNER JOIN zend_modules md ON ac.module_id = md.module_id
				WHERE zr.role_id = ? AND md.module_name = ? AND ac.action_name = ? LIMIT 1";
		//echo $sql; die();
		return (int)$this->_db->fetchOne($sql,array($roleId,$module,$action));
	}
	
	public function setRights($roleId,$actions){
		$this->_deleteRoleRights($roleId);
		foreach((array)$actions as $actionId){
			$data = array('role_id'=>$roleId,'action_id'=>$actionId);
			$this->insert($data);
		}
		$this->_resetPrimaryKey();
		return true;
	}
	
	public function setRightRoles($roleIds,$actionId){
		foreach((array)$roleIds as $roleId){
			if(!$this->_checkExistsRight($roleId,$actionId)){
				$data = array('role_id'=>$roleId,'action_id'=>$actionId);
				$this->insert($data);
			}
		}
		return true;
	}
	
	private function _checkExistsRight($roleId,$actionId){
		$sql = "SELECT right_id FROM zend_rights WHERE role_id = ? AND action_id = ? LIMIT 1";
		return  (int)$this->_db->fetchOne($sql,array($roleId,$actionId));
	}
	
	private function _resetPrimaryKey(){
		$sql = "ALTER TABLE `zend_rights` DROP COLUMN `right_id`";
		$this->_db->query($sql);
		$sql = "ALTER TABLE `zend_rights`
				ADD COLUMN `right_id` INT(10) NOT NULL AUTO_INCREMENT FIRST,
				ADD PRIMARY KEY (`right_id`)";
		$this->_db->query($sql);
	}
	
	private function _deleteRoleRights($roleId){
		$this->delete("role_id=".(int)$roleId);
	}
	
	public function getModuleRight($roleId){
		$sql = "SELECT md.module_id
				FROM zend_actions ac
				INNER JOIN zend_modules md ON ac.module_id = md.module_id
				INNER JOIN zend_rights zr ON zr.action_id = ac.action_id
				WHERE zr.role_id = ? GROUP BY md.module_id";
		$moduleList = $this->_db->fetchAll($sql,array($roleId));
		$moduleRight = array();
		foreach((array)$moduleList as $md){
			$moduleRight[] = $md['module_id'];
		}
		return $moduleRight;
	}
	
	public function getActionRight($roleId){
		$sql = "SELECT ac.action_id
				FROM zend_actions ac
				INNER JOIN zend_modules md ON ac.module_id = md.module_id
				INNER JOIN zend_rights zr ON zr.action_id = ac.action_id
				WHERE zr.role_id = ?";
		$actionList = $this->_db->fetchAll($sql,array($roleId));
		$actionRight = array();
		foreach((array)$actionList as $ac){
			$actionRight[] = $ac['action_id'];
		}
		return $actionRight;
	}
		
	public function getRightList($roleId){
		$sql = "SELECT ac.*,md.module_name,md.module_name_display,zr.right_id
				FROM zend_actions ac
				INNER JOIN zend_modules md ON ac.module_id = md.module_id
				LEFT JOIN zend_rights zr ON zr.action_id = ac.action_id AND zr.role_id = ?
				WHERE 1=1 ORDER BY md.module_name_display";
		$actionList = $this->_db->fetchAll($sql,array($roleId));
		$modules = array();
		$ids = array();
		foreach((array)$actionList as $ac){
			if(!in_array($ac['module_id'],$ids)){
				$ids[] 		= $ac['module_id'];
				$modules[] 	= array('module_id'=>$ac['module_id'],'module_name'=>$ac['module_name'],'module_name_display'=>$ac['module_name_display']);
			}
		}
		
		for($i=0;$i<sizeof($modules);$i++){
			$module = $modules[$i];
			$actions = array();
			foreach((array)$actionList as $action){
				if($module['module_id'] == $action['module_id']){
					$actions[] = array('action_id'=>$action['action_id'],'action_name'=>$action['action_name_display'],'right_id'=>$action['right_id']);
				}
			}
			$modules[$i]['actions'] = $actions;
		}		
		return $modules;
	}	
}

?>
