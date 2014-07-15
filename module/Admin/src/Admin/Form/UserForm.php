<?php
namespace Admin\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed ??
        parent::__construct('user');

        $this->add(array(
            'name' => 'userName',
            'type' => 'Text',
            'options' => array(                
               // 'label' => '',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'userName',  
                  'required' => true,
                  'placeholder' => 'Login',
                'role' => 'form'
                 
            ),
        ));
        
        $this->add(array(
            'name' => 'userPassword',
            'type' => 'Password',
            'options' => array(
               // 'label' => '',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'userPassword', 
                  'required' => true,
                  'placeholder' => 'Hasło'
                  
            ),
        ));   
        
        $this->add(array(
            'name' => 'rememberme',
            'type' => 'checkbox',
            'options' => array(
               // 'label' => '',               
            ),
            'attributes'  => array(
//                  'class' => 'checkbox',                  
                  'id'    => 'rememberme',    
            ),
        ));  
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Zaloguj',
                'id' => 'submitbutton',
                'class' => 'btn btn-lg btn-primary btn-block',
            ),
        ));
    }
}