<?php

class AprioriController extends Zend_Controller_Action {
    
    private $_ruleCandidates = array();
    private $_associationRules = array();
        
    public function init() {
        parent::init();   
    }

    public function indexAction() {
        if ($this->_request->isPost()) {
            $this->forward('calculate');
        }
        $config = Zend_Registry::get('config');
        $model = new Application_Model_Apriori();
        $this->view->assign(array(
            'db' => $config->production->resources->db,
            'data' => $model->fetchAll($model->select()->limit(100)),
            'fields' => $model->getFields()
        ));
    }

    public function calculateAction() {
        $support = $this->getParam('support', .3);
        $minCofidence = $this->getParam('minConfidence', .75);
        $dataset = $this->getParam('dataset', SET_RAW);
        $model = new Application_Model_Apriori(array('table'=>$dataset));        
        
        $fields = $model->getFields();
        $transactions = $model->getTransactionCount();

        if (!is_numeric($support) || $support <= 0 || $support > 1) {
            throw new Exception("Invalid Suppot Level Requested");
        }

        $threshhold = ceil($support * $transactions);
        $apriori = array();
        $validFields = $fields;

        for ($i = 1; $i <= count($validFields); $i++) {
            foreach ($this->combinations($validFields, $i) as $k => $configuration) {
                if ($k == 0) {
                    $validFields = array();
                }
                $itemset = array(
                    'fields' => $configuration,
                    'support' => $model->getCardinality($configuration),
                    'supportRequired' => $threshhold,
                    'query' => $model->getLastQuery()
                );
             
                // Add itemset to scan
                $apriori['levels'][$i][] = $itemset;
                
                if ($itemset['support'] >= $itemset['supportRequired']) {
                    // Repopulate necessary fields
                    foreach ($configuration as $item) {
                        if (!in_array($item, $validFields)) {
                            $validFields[] = $item;
                        }
                    }
                    
                    // Append to the association rule array
                    if(count($configuration) > 1){
                        $this->_ruleCandidates[] = $itemset;
                    }
                }
            }
        }
                
        $this->generateAssociationRules();
        
//        die(var_dump($this->_associationRules));
        
        $this->view->assign(array(
            'apriori' => $apriori['levels'],
            'associationRules' => $this->_associationRules,
            'numberTransactions' => $transactions,
            'supportDesired' => $support,
            'minimumSupport' => $threshhold,
            'minimumConfidence' => $minCofidence
        ));
    }
    
    private function generateAssociationRules(){
        
        $hashTable = array();
        foreach($this->_ruleCandidates as $k=>$itemset){
            $hashTable = array_merge($hashTable, $this->_iterateRules($itemset['fields']));
            unset($this->_ruleCandidates[$k]);
        }
        
        unset($this->_ruleCandidates);
        
        $model = new Application_Model_Apriori();
        $transactions = $model->getTransactionCount();
        
        foreach($hashTable as &$rule){
            $numerator = array_merge($rule['x'], $rule['y']);
            $denominator = $rule['x'];

            // Get cardinality
            $numerator = $model->getCardinality($numerator);

            $denominator = $model->getCardinality($denominator);

            $rule['support_x'] = $model->getCardinality($rule['x']);
            $rule['support_y'] = $model->getCardinality($rule['y']);
            $rule['numerator'] = $numerator;
            $rule['denominator'] = $denominator;
            $rule['confidence'] = $numerator/$denominator;
            $rule['lift'] = round(($rule['numerator']/$transactions)/(($rule['support_y']/$transactions) * ($rule['support_x']/$transactions)),3);
        }

        $this->_associationRules = $hashTable;
    }
    
    private function _iterateRules(Array $itemset){
        
        $rules = array();
        foreach($itemset as $k=>$v){
            $itemsetClone = $itemset;
            unset($itemsetClone[$k]);
            for($i=1;$i<count($itemset);$i++){
                foreach($this->combinations($itemset, $i) as $combo){
                    $key = md5(json_encode($combo));
                    if(!array_key_exists($key, $rules)){
                        $rules[$key] = array(
                            'x' => $combo,
                            'y' => array_diff($itemset, $combo)
                        );
                    }
                }
            }
        }
        return array_values($rules);
    }

    private function combinations($base, $n) {

        $baselen = count($base);
        if ($baselen == 0) {
            return;
        }

        if ($n == 1) {
            $return = array();
            foreach ($base as $b) {
                $return[] = array($b);
            }
            return $return;
        } else {
            //get one level lower combinations
            $oneLevelLower = $this->combinations($base, $n - 1);

            //for every one level lower combinations add one element to them that the last element of a combination is preceeded by the element which follows it in base array if there is none, does not add
            $newCombs = array();

            foreach ($oneLevelLower as $oll) {

                $lastEl = $oll[$n - 2];
                $found = false;
                foreach ($base as $key => $b) {
                    if ($b == $lastEl) {
                        $found = true;
                        continue;
                        //last element found
                    }
                    if ($found == true) {
                        //add to combinations with last element
                        if ($key < $baselen) {

                            $tmp = $oll;
                            $newCombination = array_slice($tmp, 0);
                            $newCombination[] = $b;
                            $newCombs[] = array_slice($newCombination, 0);
                        }
                    }
                }
            }
        }

        return $newCombs;
    }
    
    public function clearcacheAction(){
        apc_clear_cache();
        apc_clear_cache('user');
        die('Cache Cleared');
    }

}