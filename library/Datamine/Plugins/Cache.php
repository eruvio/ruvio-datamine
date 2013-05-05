<?php

class Datamine_Plugins_Cache
    extends Zend_Controller_Plugin_Abstract {
        
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        parent::routeShutdown($request);
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        parent::preDispatch($request);
        $key = md5(serialize($request->getParams()));
        
        $cache = Datamine_Cache::getInstance();
        
        if($response = $cache->load($key)){
            echo $response['data'];
            exit;
        }
        
        $cache->start($key);        
    }

}