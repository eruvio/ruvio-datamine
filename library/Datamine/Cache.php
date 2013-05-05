<?php

class Datamine_Cache {
    
    private static $_cache = null;
    
    private function __construct() {}
    
    /**
     * 
     * @return Zend_Cache_Frontend_Page
     */
    public static function getInstance(){
        
        if(!self::$_cache){
            $backendOptions = array(
                'cache_dir' => APPLICATION_PATH . '/cache',
                'lifetime'  => null
            );
            self::$_cache = Zend_Cache::factory('Page', 'File', array(), $backendOptions);
        }
        
        return self::$_cache;
    }
    
}