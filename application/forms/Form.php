<?php

class Application_Form_Form
    extends Zend_Form
{
    
    public function __construct($options = null) {
        // Default Path to Elements/Decorators
        $this->addPrefixPath('Application_Form_Element_', APPLICATION_PATH . '/forms/Element/', Zend_Form::ELEMENT);
        $this->addPrefixPath('Application_Form_Decorator_', APPLICATION_PATH . '/forms/Decorator/', Zend_Form::DECORATOR);
     
        // Default form Method
        $this->setMethod(Zend_Form::METHOD_POST);
        
        // Form decorators
        $this->setDecorators(array(
            'FormErrors',
            'BootstrapElement',
            array('Form', array('class' => 'form'))
        ));
//
//        // Default Form Decorators
//        $this->setDecorators(array(
//            array('FormErrors'),
//            'FormElements',
//            array('HtmlTag', array('tag' => 'fieldset', 'class' => 'widelabels')),
//            array('Form', array('class' => 'form'))
//        ));

//        // Default Element decorators
//        $this->setElementDecorators(array(
//            'ViewHelper',
//            //'Errors',
//            array('Description', array('escape' => false, 'tag' => 'span')),
//            array('Label'),
//            array(array('row' => 'HtmlTag'), array('tag' => 'p'))
//        ));

        $this->setElementFilters(array('StringTrim'));

        parent::__construct($options);
    }
}