<?php
class Administrator_ErrorController extends Zend_Controller_Action{
public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        if ($errors) {
        	//if(SERVER_ENVIRONMENT === 'localhost'){
        		echo "<pre>";
        		print_r($errors);
        		die();
        		try{
        			$writer = new Zend_Log_Writer_Stream('/var/www/html/wish.vn/www/log_zend/log.txt');
        			$logger = new Zend_Log($writer);
        			$logger->info(print_r($errors,true));
        		
        		}  catch (Zend_Exception $e) {
        			echo "Caught exception: " . get_class($e) . "\n";
        			echo "Message: " . $e->getMessage() . "\n";
        		}
        	//}
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
