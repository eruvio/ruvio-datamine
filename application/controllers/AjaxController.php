<?php

class AjaxController
    extends Sparx_MainController {
    
    public function init(){
        parent::init();
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function loadprofilesAction(){
        $modelGeo = new Application_Model_Geo();
        $modelProfile = new Application_Model_Profile();
        $params = $this->filterParams($this->getAllParams());
        $status = null;
        $looking_for = $zipcodes = $age = array();

        /*********************
         * Gender
         *********************/
        if(isset($params['looking_for_1']) || isset($params['looking_for_2'])){
            if(isset($params['looking_for_1'])){
                $looking_for[] = 1;
            }
            if(isset($params['looking_for_2'])){
                $looking_for[] = 2;
            }
        }
        if(isset($params['looking_for_3']) || isset($params['looking_for_4'])){
            if(isset($params['looking_for_3'])){
                $looking_for[] = 3;
            }
            if(isset($params['looking_for_4'])){
                $looking_for[] = 4;
            }
        }
        
        /*********************
         * Location
         *********************/
        $proximity = isset($params['proximity']) ? intval($params['proximity']) : null;
        if(isset($params['zip']) && !empty($params['zip'])){
            $zipcodes = $modelGeo->getZipcodesByProximityToZip($params['zip'],$proximity);
        } elseif(isset($params['city']) && isset($params['state'])){
            $zipcodes = $modelGeo->getZipcodesByProximityToCity($params['city'], $params['state'], $proximity);
        }
        
        
        /*********************
         * Age
         *********************/
        if(isset($params['age_lb']) && is_numeric($params['age_lb'])){
            $age['lowerbound'] = intval($params['age_lb']);
        }
        if(isset($params['age_ub']) && is_numeric($params['age_ub'])){
            $age['upperbound'] = intval($params['age_ub']);
        }
        
                
        /*********************
         * Status
         *********************/
        if(isset($params['status'])){
            if($params['status'] === 'online'){
                $status = '1';
            }
        }
                
        die($this->view->renderProfiles($modelProfile->search($looking_for, $zipcodes, $age, $status)));
    }
    
    private function filterParams(Array $params){
        $form = isset($params['form']) ? (string)$params['form'] :'';
        $form = explode('&', $form);
        $formValues = array();
        foreach($form as $value){
            $value = explode('=', $value);
            @$formValues[$value[0]] = str_replace('+', ' ', htmlentities($value[1]));
        }
        return $formValues;
    }
    
    public function __call($methodName, $args) {
        $this->redirect('/profile');
    }
    
}