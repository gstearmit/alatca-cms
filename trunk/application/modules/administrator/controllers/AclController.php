<?php
class Administrator_AclController extends Zend_Controller_Action{
	public function init() {
		
	}
	
	public function indexAction(){
		$objAcl = new HT_Model_administrator_models_acl();
		$objRole = new HT_Model_administrator_models_role();
		$roleId = @$this->_request->getParam('roleId');
		$status = @$this->_request->getParam('status');
		if($roleId >0){
			$rightList = $objAcl->getRightList($roleId);
			$role	   = $objRole->getRole($roleId);
			$this->view->rightList 	= $rightList;
			$this->view->roleId 	= $roleId;
			$this->view->status 	= $status;
			$this->view->role 		= $role;
		}else{
			$this->_redirect(WEB_PATH.'/administrator/index/denied');
		}
	}
	
	public function updateAction(){
		$objAcl 	= new HT_Model_administrator_models_acl();
		$roleId 	= @$this->_request->getParam('roleId');
		if($roleId >0){
			$actions 	= @$this->_request->getParam('actions');
			$objAcl->setRights($roleId,$actions);
			//$this->_redirect(WEB_PATH.'/administrator/acl/?roleId='.$roleId.'&status=1');
			$this->_redirect(WEB_PATH.'/administrator/role');
		}else{
			$this->_redirect(WEB_PATH.'/administrator/index/denied');
		}
	}
	
	public function reloadAction(){
		$objAcl 	= new HT_Model_administrator_models_acl();
		$status 	= (int)@$this->_request->getParam('status');
		$fileList	= $this->_getAdminFiles();
		if($status != 1){
			if($objAcl->autoUpdateModules($fileList)){
				$this->_redirect(WEB_PATH.'/administrator/acl/reload/?status=1');
			}
		}
		$this->view->status = $status;
		
		
	}
	
	private function _getAdminFiles(){
		$front = $this->getFrontController();
		$fileList = array();
		foreach ($front->getControllerDirectory() as $module => $path) {
			$fileItem = array();
			$adminDir1 = APPLICATION_PATH.'/modules/administrator/controllers';
			$adminDir2 = APPLICATION_PATH.'/modules/admin/controllers';
			if($path == $adminDir1 || $path == $adminDir2){
				foreach (scandir($path) as $file) {
					if (strstr($file, "Controller.php") !== false) {
						$filePath = $path . DIRECTORY_SEPARATOR . $file;
						$fileItem['filePath'] 	= $filePath;
						$fileItem['fileName'] 	= $file;
						$fileList[] = $fileItem;
					}
				}
			}
		}	
		return $fileList;	
	}	
}
