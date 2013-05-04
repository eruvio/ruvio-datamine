<?php

class Application_Model_CrossSelling
    extends Zend_Db_Table_Abstract
{
    protected $_name = 'cross_selling';
    const ACTIVE = 1;
    
    public function __construct($config = array()) {
        $this->_name = isset($config['table']) ? $config['table'] :  SET_RAW;
        
        if($this->_name != SET_RAW && $this->_name != SET_PREPROCESSED){
            throw new Exception("Illegal dataset in " . __CLASS__);
        }
        
        parent::__construct($config);
    }
    
    public function getTransactionCount(){
        return $this->_db->fetchOne("select count(0) from " . $this->_name);
    }
    
    public function getFields(){
        return array_slice($this->_getCols(), 1);
    }
    
    public function getCardinality(Array $fields){
        $select = $this->select()->from($this, array('count(0)'));
        foreach($fields as $field){
            $select->where("`$field`=?", self::ACTIVE);
        }
        $this->_lastQuery = $select->assemble();
        
        $cacheKey = md5($this->_lastQuery . '_table_' . $this->_name);
        
        if(extension_loaded('APC')){
            if($result = apc_fetch($cacheKey)){
                return $result;
            }
            $result = $this->_db->fetchOne($select);
            apc_store($cacheKey, $result);
        } else {
            $result = $this->_db->fetchOne($select);
        }
        return $result;
    }

}