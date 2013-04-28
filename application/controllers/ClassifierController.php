<?php

class ClassifierController extends Zend_Controller_Action {

    public function indexAction() {
        
        
        apc_store('test', 'hello world');
        
        die(var_dump(apc_fetch('test')));
        
        if ($this->_request->isPost()) {
            $this->forward('calculate');
        }
        $config = Zend_Registry::get('config');
        $model = new Application_Model_Apriori();
        $this->view->assign(array(
            'db' => $config->production->resources->db,
            'data' => $model->fetchAll(),
            'fields' => $model->getFields()
        ));
    }

    public function calculateAction() {
        $categories = array_keys($this->getParam('options', array()));
        die(var_dump($categories));
        }

}

