<?php

class IndexController
    extends Zend_Controller_Action {

    
    public function indexAction()
    {
        $cache = Datamine_Cache::getInstance();
        $cache->cancel();
        $this->redirect($this->view->url(array('controller'=>'apriori')));
    }
    
}

