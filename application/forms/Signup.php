<?php

class Application_Form_Signup 
    extends Application_Form_Form
{

    public function init()
    {
        

        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add an email element
        $this->addElement('text', 'email', array(
            'label'      => 'Email',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));
        
        
        // Add an email element
        $this->addElement('text', 'password', array(
            'label'      => 'Email',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));
        

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Sign up',
        ));

//        $this->addElement('hash', 'csrf', array(
//            'ignore' => true,
//        ));
    }
}
