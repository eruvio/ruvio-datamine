<?php

class Zend_View_Helper_RenderLevel
    extends Zend_View_Helper_Abstract
{
    /**
     * Generates a table for an apriori level
     * @param array $level
     */
    public function renderLevel(Array $level) {
        static $i=1;
        $html = '<fieldset><legend>Scan #' . $i++ . '</legend>';
        $html .= '<table class="table table-bordered table-hover">';
        $html .= '<th>Itemset</th><th>Support</th>';
        foreach($level as $row){
            $rowClass = 'alert-' . ($row['support'] < $row['supportRequired'] ? 'error' : 'success');
            $html .= '<tr>';
            $html .= '<td class="' . $rowClass . '"><a href="#" data-toggle="tooltip" title="' . ($row['query']) . '">' . implode(', ', $row['fields']) . '</a></td>';
            $html .= '<td class="' . $rowClass . '">' . $row['support'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
            
//        foreach($level as $row){
//            $html .= '<div class="progress progress-striped active">
//            <div class="bar" style="width: ' . (min(array(1,$row['support'] / $this->view->minimumSupport))*100) . '%;"><p>' . (implode(', ', $row['fields'])) . '</p></div>
//          </div>';
//        }

        $html .= '</fieldset>';
        echo $html;
    }

}
