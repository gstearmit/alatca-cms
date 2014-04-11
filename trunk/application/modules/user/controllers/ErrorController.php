<?php
class User_ErrorController extends Zend_Controller_Action{
public function errorAction()
    {
    	$errors = $this->_getParam('error_handler');
    	if ($errors) {
    		if(SERVER_ENVIRONMENT === 'localhost'){
    			echo "<pre>";
    			print_r($errors);
    			die();
    		}else{
    			$this->_redirect(WEB_PATH."/404.html");
    		}
    	}
    }

    public function getLog(){
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}
