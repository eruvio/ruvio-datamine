<?php

class IndexController
    extends Zend_Controller_Action {

    
    public function indexAction()
    {
        $this->redirect($this->view->url(array('controller'=>'apriori')));
    }
    
}

