<?php
namespace Admin\Form;

use Zend\Form\Form;

class InfoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed ??
        parent::__construct('info');

        $this->add(array(
            'name' => 'infoId',
            'type' => 'Hidden',
        ));
         
        $this->add(array(
            'name' => 'infoName',
            'type' => 'Text',
            'required' => true,
            'options' => array(                
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoName',  
                  'required' => true,
                 
            ),
        ));
        
        $this->add(array(
            'name' => 'infoStreet',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoStreet', 
                  
                  
            ),
        ));  
        
                
        $this->add(array(
            'name' => 'infoCity',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoCity', 
                  
                  
            ),
        ));  
        
        $this->add(array(
            'name' => 'infoPhone',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoPhone', 
//                  'required' => true,
                  
            ),
        ));   
        
        $this->add(array(
            'name' => 'infoCellPhone',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoCellPhone', 
                 
                  
            ),
        ));  
        
        $this->add(array(
            'name' => 'infoEmail',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoEmail', 
                  
                  
            ),
        )); 
        
        $this->add(array(
            'name' => 'infoFax',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoFax', 
                 
                  
            ),
        ));
        
        $this->add(array(
            'name' => 'infoNip',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoNip', 
                 
                  
            ),
        ));
        
        $this->add(array(
            'name' => 'infoRegon',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoRegon', 
                  
                  
            ),
        ));
            
        $this->add(array(
            'name' => 'infoHours',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'infoHours', 
                  
                  
            ),
        ));
        
        $this->add(array(
            'name' => 'submitInfo',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Zapisz zmiany',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary',
            ),
        ));
    }
}