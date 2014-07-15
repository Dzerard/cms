<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Design
{
    public $designId;
    public $designFog;    
    public $designLights;
    public $designPlane;
    public $designUpdate;
        
    protected $inputFilter;

   
    public function exchangeArray($data)
    {
        
        $this->designId      = (!empty($data['designId'])) ? $data['designId'] : null;
        $this->designFog     = (!empty($data['designFog'])) ? $data['designFog'] : null;
        $this->designLights  = (!empty($data['designLights'])) ? $data['designLights'] : null;
        $this->designPlane   = (!empty($data['designPlane'])) ? $data['designPlane'] : null; 
        $this->designUpdate  = (!empty($data['designPlane'])) ? $data['designUpdate'] : null; 
        //dodatkowe wkrotce
    }
  
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    // @todo walidacja po stronie serwera
//    public function getInputFilter()
//    {
//        
//    }
}