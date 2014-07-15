<?php

namespace Admin\Form;

use Zend\Form\Form;

class DesignForm extends Form
{
    public function __construct($name = null)
    {
       
        parent::__construct('design');

        $this->add(array(
            'name' => 'designId',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'designFog',
            'type' => 'hidden',
            'options' => array(
                //'label' => 'Tytuł wiadomości',                  
            ),
            'attributes' => array(
                  'class'         => 'form-control',                 
                  'id'            => 'designFog',
            ),
        ));    
        $this->add(array(
            'name' => 'designLights',
            'type' => 'hidden',
            'options' => array(
                //'label' => 'Tytuł wiadomości',                  
            ),
            'attributes' => array(
                  'class'         => 'form-control',                 
                  'id'            => 'designFog',
            ),
        ));   
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Zapisz',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary',
                
            ),
        ));
    }
    
    
    
}