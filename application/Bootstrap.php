<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload() {
	$loader = Zend_Loader_Autoloader::getInstance();
	$loader->registerNamespace('Datamine_');
    }
    protected function _initDoctype(){
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    
    protected function _initMVC(){
        Zend_Layout::startMvc();
        $this->bootstrap('view');
    }
    
    protected function _initConfig(){
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini');
        Zend_Registry::set('config', $config);
    }


    protected function _initDatabase(){
        $this->bootstrap('db');
    }
    
    protected function _initNavigation(){
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $container = include(APPLICATION_PATH . '/configs/navigation.php');
        $view->navigation(new Zend_Navigation($container));
    }
    
    protected function _initConstants(){
        define('SET_RAW', 'cross_selling');
        define('SET_PREPROCESSED', 'cross_selling_preprocessed');
    }
    
    protected function _initPlugins(){
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new Datamine_Plugins_Cache());
    }
    
}