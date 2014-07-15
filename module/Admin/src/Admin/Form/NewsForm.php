<?php

namespace Admin\Form;

use Zend\Form\Form;

class NewsForm extends Form
{
    public function __construct($name = null)
    {
       
        parent::__construct('admin');

        $this->add(array(
            'name' => 'newsId',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'newsTitlePl',
            'type' => 'Text',
            'options' => array(
                //'label' => 'Tytuł wiadomości',   
               
            ),
            'attributes' => array(
                  'class'         => 'form-control',
                  'placeholder'   => 'wpisz treść ...',
                  'id'            => 'newsTitlePl',
            ),
        ));
      
        $this->add(array(
            
            'name' => 'newsMessagePl',
            'type' => 'textarea',
            'options' => array(
                //'label' => 'Treść wiadomości',   
                
            ),
            'attributes' => array(
                  'class'         => 'form-control',
                 // 'rows'          => '10',
                  'placeholder'   => 'wpisz treść ...',
                  'id'            => 'newsMessagePl',
            ),
        ));
      
        $this->add(array(
            'name' => 'newsPicture',
            'type' => 'file',
            'options' => array(
               // 'label' => 'Dodatkowe zdjęcie',
            ),
            'attributes' => array(
                 'id'   => 'newsPicture',
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