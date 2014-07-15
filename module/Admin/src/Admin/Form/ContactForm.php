<?php
namespace Admin\Form;

use Zend\Form\Form;

class ContactForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('contact');

        $this->add(array(
            'name' => 'contactId',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'contactName',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'contactEmail',
            'type' => 'Text',
            'options' => array(
                'label' => 'Email',
            ),
        ));
        
        $this->add(array(
            'name' => 'contactSubject',
            'type' => 'Text',
            'options' => array(
                'label' => 'Subject',
            ),
        ));
        $this->add(array(
            'name' => 'contactPosition',
            'type' => 'Text',
            'options' => array(
                'label' => 'Position',
            ),
        ));
        $this->add(array(
            'name' => 'contactPhone',
            'type' => 'Text',
            'options' => array(
                'label' => 'Phone',
            ),
        ));
        
        $this->add(array(
            'name' => 'contactMessage',
            'type' => 'Text',
            'options' => array(
                'label' => 'Message',
            ),
        ));
     
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}