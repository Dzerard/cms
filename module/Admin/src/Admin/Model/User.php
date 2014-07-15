<?php
namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
class User implements InputFilterAwareInterface
{
   
    public $userName;
    public $userPassword;
    public $rememberme;
    
    protected $inputFilter;


    public function exchangeArray($data)
    {
        
        $this->userName           = (!empty($data['userName'])) ? $data['userName'] : null;
        $this->userPassword       = (!empty($data['userPassword'])) ? $data['userPassword'] : null;
        $this->rememberme         = (!empty($data['rememberme'])) ? $data['rememberme'] : null;
       
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'userName',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name'     => 'userPassword',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
        
    }
    
}