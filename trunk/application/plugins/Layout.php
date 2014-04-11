<?php

class HT_Plugin_Layout extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $front_controller = Zend_Controller_Front::getInstance();
        $error_handler = $front_controller->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        $error_handler->setErrorHandlerModule($module);

        // check the module and automatically set the layout
        $layout = Zend_Layout::getMvcInstance();

        switch ($module) {
            case 'administrator':
                if ($controller == "login") {
                    $layout->setLayout('login/index');
                }
                $layout->setLayout('flatty/index');
                break;
            case 'user':
                $layout->setLayout('front/index');
                break;
            case 'detail':
                $layout->setLayout('detail');
                break;
             case 'catenews':
                $layout->setLayout('catenews');
                break;
            default:
                $layout->setLayout('index');
                break;
        }
    }

}
