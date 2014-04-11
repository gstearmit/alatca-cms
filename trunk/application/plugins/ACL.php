<?php

class HT_Plugin_ACL extends Zend_Controller_Plugin_Abstract {

	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$objAcl 			= new HT_Model_administrator_models_acl();
		$currentModule 		= $request->getModuleName();
		$currentController 	= $request->getControllerName();		
		$action				= $request->getActionName();
		
		/*
		 * Bởi vì chỉ phân quyền khi truy cập vào module Admin, nên ta chỉ kiểm soát module admin chứ không phải tất cả các module khác.
		 * Ta coi controllers là các module độc lập và kiểm soát quyền truy cập vào các controller
		 * */
		
		if($currentModule == 'administrator' || $currentModule == 'admin'){
			$module 	= $currentController;
			
			$r 			= new Zend_Controller_Action_Helper_Redirector;
			@$auth 		= Zend_Auth::getInstance();
			$user 		= @$auth->getStorage()->read();
			if(isset($user->role_id) && (int)$user->role_id >0){
				$roleId		= $user->role_id;
				//echo $roleId.'_____'.$module.'_______'.$action;
				$right 		= $objAcl->checkRightAccess($roleId,$module,$action);
				if($module == 'index' || $module == 'error'){
					// do nothing
				}elseif(!$right){
					if(SERVER_ENVIRONMENT != 'localhost'){
	    			 	$r->gotoUrl(WEB_PATH.'/'.$currentModule.'/index/denied')->redirectAndExit();
					}
				}
			}else{
				$r->gotoUrl(WEB_PATH);
			}			
		}		
	}
}
