<?php

class AprioriController extends Zend_Controller_Action {

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
        $model = new Application_Model_Apriori();
        $support = $this->getParam('support', .3);

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
                    'supportRequired' => $threshhold
                );
                $apriori['levels'][$i][] = $itemset;
                if ($itemset['support'] >= $itemset['supportRequired']) {
                    foreach ($configuration as $item) {
                        if (!in_array($item, $validFields)) {
                            $validFields[] = $item;
                        }
                    }
                }
            }
        }

        $this->view->assign(array(
            'apriori' => $apriori['levels'],
            'numberTransactions' => $transactions,
            'supportDesired' => $support,
            'minimumSupport' => $threshhold
        ));
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

}

