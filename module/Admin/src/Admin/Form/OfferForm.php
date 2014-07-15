<?php

namespace Admin\Form;

use Zend\Form\Form;

class OfferForm extends Form
{
    
    public static function getCategories() {
        
    return array(
       
        'office'         => 'biuro i administracja',
        'budownictwo'    => 'budownictwo',                
        'hr_kadry'       => 'hr, kadry i rekrutacja',
        'telekom'        => 'telekomunikacja',  
        'energetyka'     => 'energetyka',
        'elektryka'      => 'elektryka/elektronika',
        'informatyka'    => 'informatyka'
        
        );
        
    }
    
    public function __construct($name = null)
    {
       
        parent::__construct('admin');

        $this->add(array(
            'name' => 'offerId',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'offerTitle',
            'type' => 'Text',
            'options' => array(),          
            'attributes' => array(
                  'id'          => 'offerTitle',    
                  'class'       => 'form-control',
                  'placeholder' => 'wpisz treść ...',
                ),
        ));
        
        $this->add(array(
            'name' => 'offerDesc',
            'type' => 'textarea',
            'options' => array(
               // 'label' => 'Opis stanowiska',               
            ),
            'attributes'  => array(
                  'class' => 'form-control',                  
                  'id'    => 'offerDesc',    
            ),
        ));
        
        $this->add(array(
            'name' => 'offerExtraInfo',
            'type' => 'textarea',
            'options' => array(
             //   'label' => 'Dodatkowe informacje',              
            ),
            'attributes' => array(
                  'class'        => 'form-control',
                  'rows'         => '10',
                  'placeholder'  => '...',
                  'id'           => 'offerExtraInfo',
            ),
        ));
        
        $this->add(array(
            'name' => 'offerType',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Stanowisko',                
            ),
            'attributes' => array(
                  'class' => 'form-control',
                  'id'    => 'offerType',
            ),
        ));
        
        $this->add(array(
            'name' => 'offerCountry',
            'type' => 'select',
            'options' => array(
               // 'label' => 'Kraj',  
                'value_options' => array(
                    'pl'     =>' Polska',
                    'de'     =>' Niemcy',
                    'en'     =>' Anglia',
                    'usa'    =>' USA',
                    'can'    =>' Kanada',
                ),               
            ),
            'attributes' => array(
                 'id'    => 'offerCountry',    
                 'class' => 'form-control',
            ),
        ));
        
        $this->add(array(
            'name' => 'offerCity',
            'type' => 'Text',
            'options' => array(
               //'label' => 'Adres (Miasto)',              
            ),
            'attributes' => array(
                  'class' => 'form-control',
                  'id'    => 'offerCity',
            ),
        ));
        
        $this->add(array(
            'name' => 'offerStreet',
            'type' => 'Text',
            'options' => array(
               // 'label' => 'Pracodawca',                 
            ),
            'attributes' => array(
                  'class' => 'form-control',
                  'id'    => 'offerStreet',
            ),
        ));
        
        $this->add(array(
            'name' => 'offerNumber',
            'type' => 'Hidden',
            'options' => array(
                //'label' => 'Nr ref.',                 
            ),
            'attributes' => array(
                  'class' => 'form-control',
                  'id'    => 'offerNumber',                  
            ),
        ));
        
        $this->add(array(
            'name' => 'offerCategory',
            'type' => 'Select',
            'options' => array(
               //'label' => 'Kategoria',  
                'empty_option' => 'Wybierz kategorie',
                'value_options' => OfferForm::getCategories(),
            ),
            'attributes' => array(
                  'class' => 'form-control',
                  'id'    => 'offerCategory',                 
            ),
        ));
        
        $this->add(array(
            'name' => 'offerWebPage',
            'type' => 'Text',
            'options' => array(
                //'label' => 'Strona www',                  
            ),
            'attributes' => array(
                  'class' => 'form-control',   
                  'id'    => 'offerWebPage', 
            ),
        ));
        
        $this->add(array(
            'name' => 'offerEmail',
            'type' => 'Text',
            'options' => array(
               //'label' => 'Email',   
               
            ),
            'attributes' => array(
                  'class'        => 'form-control',
                  'placeholder'  => 'email ...',
                  'id'           => 'offerEmail',
                  'required'     => true,
            ),
        ));
        
        $this->add(array(
            'name' => 'offerPhone',
            'type' => 'Text',
            'options' => array(
                //'label' => 'Telefon',                  
            ),
            'attributes' => array(
                  'class' => 'form-control',  
                  'id'    => 'offerPhone',
            ),
        )); 
        
        $this->add(array(
            'name' => 'offerVisible',
            'type' => 'checkbox',
            'attributes' => array(                  
                  'id'    => 'offerVisible',
                  //'value' => 0
            ),
        ));
        
        $this->add(array(
            'name' => 'offerImage',
            'type' => 'file',
            'options' => array(
                //'label' => 'Dodatkowe zdjęcie',
            ),
            'attributes' => array(                  
                  'id'    => 'offerImage',
            ),
        ));
        
        $this->add(array(
            'name' => 'offerVideo',
            'type' => 'text',
            'options' => array(
                //'label' => 'Dodatkowe zdjęcie',
            ),
            'attributes' => array(                  
                'id'    => 'offerImage',
                'class' => 'form-control'                
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