<?php

class Application_Model_CrossSelling
    extends Zend_Db_Table_Abstract
{
    protected $_name = 'cross_selling';
    const ACTIVE = 1;
    
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
        
        $cacheKey = md5($this->_lastQuery);
        
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