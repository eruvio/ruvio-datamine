<?php

class ClassifierController extends Zend_Controller_Action {

    public function indexAction() {
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
        $categories = $this->getParams('options', array());
        }
    
    public function mongotestsAction(){
//        $connection = new MongoClient();
    }


}

