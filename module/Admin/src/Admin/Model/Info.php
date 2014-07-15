<?php
namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Info implements InputFilterAwareInterface
{
    public $infoId;
    public $infoName;
    public $infoStreet;
    public $infoCity;
    public $infoPhone;
    public $infoCellPhone;
    public $infoEmail;
    public $infoFax;
    public $infoUpdate;
   
    protected $inputFilter;


    public function exchangeArray($data)
    {
        $this->infoId         = (!empty($data['infoId'])) ? $data['infoId'] : null;
        $this->infoName       = (!empty($data['infoName'])) ? $data['infoName'] : null;        
        $this->infoStreet     = (!empty($data['infoStreet'])) ? $data['infoStreet'] : null;
        $this->infoCity       = (!empty($data['infoCity'])) ? $data['infoCity'] : null;
        $this->infoPhone      = (!empty($data['infoPhone'])) ? $data['infoPhone'] : null;
        $this->infoCellPhone  = (!empty($data['infoCellPhone'])) ? $data['infoCellPhone'] : null;   
        $this->infoEmail      = (!empty($data['infoEmail'])) ? $data['infoEmail'] : null;        
        $this->infoFax        = (!empty($data['infoFax'])) ? $data['infoFax'] : null;      
        $this->infoUpdate     = (!empty($data['infoUpdate'])) ? $data['infoUpdate'] : null;

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
                'name'     => 'infoId',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'infoName',
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
                'name'     => 'infoStreet',   
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
                'name'     => 'infoCity',
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
                'name'     => 'infoPhone',
                'required' => false,
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