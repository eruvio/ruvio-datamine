<?php

class Application_Model_Classifier
    extends Application_Model_CrossSelling
{
    public $_lastQuery = null;
    
    public function getLastQuery(){
        return $this->_lastQuery;
    }
}