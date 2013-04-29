<?php

class Zend_View_Helper_RenderAssociationRules
    extends Zend_View_Helper_Abstract
{
    /**
     * Generates a table for the association rules
     * @param array $rules
     */
    public function renderAssociationRules(Array $rules, $minConfidence) {
        static $i=1;
        $html = '';
        $html .= '<table id="associationRules" class="table table-bordered table-hover">';
        $html .= '<thead><tr><th>Rule #</th><th>Confidence %</th><th>X</th><th>Y</th><th>Support (x)</th><th>Support (y)</th><th>Support (XUY)</th><th>Lift</th></tr></thead>';
        foreach($rules as $row){
            $rowClass = 'alert-' . ($row['confidence'] < $minConfidence ? 'block' : 'info');
            $html .= '<tr class="' . $rowClass . '">';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . round($row['confidence'], 4)*100 . '%</td>';
            $html .= '<td>' . implode(', ', $row['x']) . '</td>';
            $html .= '<td>' . implode(', ', $row['y']) . '</td>';
            $html .= '<td>' . $row['support_x'] . ' (' . round($row['support_x']/$this->view->numberTransactions,2)*100 . '%)</td>';
            $html .= '<td>' . $row['support_y'] . ' (' . round($row['support_y']/$this->view->numberTransactions,2)*100 . '%)</td>';
            $html .= '<td>' . $row['numerator'] . ' (' . round($row['numerator']/$this->view->numberTransactions,2)*100 . '%)</td>';
            $html .= '<td>' . $row['lift'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table></fieldset>';
        return $html;
    }

}