<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    function _initAutoLoad() {
        $autoLoader = Zend_Loader_Autoloader::getInstance();

        $resourceLoader = new Zend_Loader_Autoloader_Resource(
        array(
                'basePath'     =>   APPLICATION_PATH,
                'namespace'  =>  'HT',
                'resourceTypes'  =>   array (
                        'form'   =>  array (
                                'path'        =>   'forms', 
                                'namespace'   =>   'Form_'), 
                        'validate'   =>  array (
                                'path'        =>   'views/validation', 
                                'namespace'   =>   'Validate_'),        
                        'plugin'   =>  array (
                                'path'        =>   'plugins', 
                                'namespace'   =>   'Plugin_'),
                        'service'   =>  array (
                                'path'        =>   'services', 
                                'namespace'   =>   'Service_'),
                        'model'   =>  array (
                                'path'        =>   'modules', 
                                'namespace'   =>   'Model_'),
                        'helper'   =>  array (
                                'path'        =>   'helpers', 
                                'namespace'   =>   'Helper_'),
            ),
        ));
        
        $autoLoader->setFallbackAutoloader(true);
        
        //Call Zend_Acl plugin
        $zcf = Zend_Controller_Front::getInstance();
        //$zcf->registerPlugin(new Butler_Plugin_Acl());

        return $autoLoader;
    } 
    protected function _initTimeZone()
    {
        // to use default GMT date time
        date_default_timezone_set('Asia/Saigon');        
    }
    
    protected function _initPlugins()
	{	    
	    $this->bootstrap('frontController');
	    // The ACL
	    $this->frontController->registerPlugin(new HT_Plugin_Layout());	    
	    $this->frontController->registerPlugin(new HT_Plugin_ACL());
	}
    protected function _initSession() {
        Zend_Session::start();
        $namespace = new Zend_Session_Namespace('Zend_Auth');
        $namespace->setExpirationSeconds(604800);
    }

    protected function _initDb() {
        $db = Zend_Db::factory("PDO_MYSQL", array(
                    "host" => "localhost",
                    "username" => "root",
                    "password" => "",
                    "dbname" => "ezpos2",
                ));
/*         $db2 = Zend_Db::factory("PDO_MYSQL", array(
                    "host" => "localhost",
                    "username" => "root",
                    "password" => "",
                    "dbname" => "mantis",
                    "charset" => "utf8"
        ));
        $db2->setFetchMode(Zend_Db::FETCH_ASSOC);
        $db2->query("SET NAMES 'utf8'");
        $db2->query("SET CHARACTER SET 'utf8'");  */
        Zend_Db_Table::setDefaultAdapter($db);
		
		
		

        // Add the DB Adaptor to the registry if we need to call it outside of the modules.
        Zend_Registry::set('dbMain', $db);
		Zend_Registry::get('dbMain')->query("SET NAMES 'utf8'");
        Zend_Registry::get('dbMain')->query("SET CHARACTER SET 'utf8'");
       // Zend_Registry::set('dbSub', $db2);
    }

    protected function _initMail() {
        try {


            //$config = array(
//                'auth' => 'login',
//                'username' => 'ht.hipt@gmail.com',
//                'password' => 'ht.hipt123',
//                'ssl' => 'ssl',
//                'port' => 465
//            );
            
            $config = array(
                'auth' => 'login',
                'username' => 'wish@hipt.com.vn',
                'password' => 'wish',
                'port' => 25
            );

            $mailTransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            Zend_Registry::set('configEmail', $config);
            Zend_Registry::set('emailHost', 'smtp.gmail.com');
            Zend_Mail::setDefaultTransport($mailTransport);
        } catch (Zend_Exception $e) {
            //Do something with exception
        }
    }
    
    protected function _initFrontController() {
    	$front = Zend_Controller_Front::getInstance();
    	$front->setDefaultModule('front');
    	$front->setControllerDirectory(
    			array(
    					'front' => APPLICATION_PATH . "/modules/front/controllers",
    					'news' => APPLICATION_PATH . "/modules/news/controllers",
    					'hardware' => APPLICATION_PATH . "/modules/hardware/controllers",
    					'user' => APPLICATION_PATH . "/modules/user/controllers",
					    'product' => APPLICATION_PATH . "/modules/product/controllers",
					    'prix' => APPLICATION_PATH . "/modules/prix/controllers",
					    'telecharger' => APPLICATION_PATH . "/modules/telecharger/controllers",
    					'minscrire' => APPLICATION_PATH . "/modules/minscrire/controllers",
    					'administrator' => APPLICATION_PATH . "/modules/administrator/controllers",  					
    			));
    
    	$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routers.ini', 'thietlap');
    	$router = new Zend_Controller_Router_Rewrite();
    	$router = $router->addConfig($config, 'routes');
    	$front->setRouter($router);
        
    	return $front;
    }

}
