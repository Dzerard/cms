<?php

namespace Admin\Form;

use Zend\Form\Form;

/**
 * Description of visualizationForm
 *
 * @author Łukasz
 */
class VisualizationForm extends Form
{
    public function __construct($name = null)
    {
       
        parent::__construct('admin');

        $this->add(array(
            'name' => 'visualizationId',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'visualizationElement',
            'type' => 'radio',
            'options' => array(
               // 'label' => 'Element',  
                'value_options' => array(
                    'cube'      => 'Kwadrat',
                    'rectangle' => 'Kula',
                    'other'     => 'Stoisko',
                    'file'      => 'Plik',
                ),               
            ),
            'attributes' => array(               
              //   'id'    => 'visualizationElement',               
            ),
        ));   
        $this->add(array(
            'name' => 'visualizationElementSize',
            'type' => 'hidden',
            'options' => array(),          
            'attributes' => array(
                 'class'    => 'form-control',   
                 'required' => true,
                 'id'       => 'visualizationElementSize',               
            ),
        )); 
        $this->add(array(
            'name' => 'visualizationElementScale',
            'type' => 'hidden',
            'options' => array(),          
            'attributes' => array(
                 'class'    => 'form-control',   
                 'required' => true,
                 'id'       => 'visualizationElementScale',               
            ),
        )); 
        
        $this->add(array(
            'name' => 'visualizationColor',
            'type' => 'hidden',
            'options' => array(),          
            'attributes' => array(
                 'class'    => 'form-control',   
                 'required' => true,
                 'id'       => 'visualizationColor',               
            ),
        )); 
      
        $this->add(array(
            'name' => 'visualizationElementCode',
            'type' => 'textarea',
            'options' => array(),          
            'attributes' => array(
                 'class'    => 'form-control codeText',   
                 'rows' => '20',
                // 'required' => true,
                 'id'       => 'visualizationElementCode',               
            ),
        )); 
        
        $this->add(array(
            'name' => 'visualizationElementFile',
            'type' => 'file',
            'options' => array(
               // 'label' => 'Dodatkowe zdjęcie',
            ),
            'attributes' => array(
                 'id'     => 'visualizationElementFile',
                 'title'  => 'Dodaj plik *.js',                 
                 'accept' => 'image/gif, image/jpeg', // tak ekstra
            ),
        ));
    }
}

?>
