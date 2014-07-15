<?php
namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class News implements InputFilterAwareInterface
{
    public $newsId;
    public $newsTitlePl;    
    public $newsMessagePl;    
    public $newsPicture;
    public $newsInsert;

    protected $inputFilter;


    public function exchangeArray($data)
    {
        
        $this->newsId           = (!empty($data['newsId'])) ? $data['newsId'] : null;
        $this->newsTitlePl      = (!empty($data['newsTitlePl'])) ? $data['newsTitlePl'] : null;
        $this->newsMessagePl    = (!empty($data['newsMessagePl'])) ? $data['newsMessagePl'] : null;        
        $this->newsPicture      = (!empty($data['newsPicture'])) ? $data['newsPicture'] : null;
        $this->newsInsert       = (!empty($data['newsInsert'])) ? $data['newsInsert'] : null;              

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
                'name'     => 'newsId',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'newsTitlePl',
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
                'name'     => 'newsMessagePl',
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
                   
//            
//            dodac walidacje pliku
//            $inputFilter->add(array(
//                'name'     => 'newsPicture',
//                'required' => false,
//                'filters'  => array(
//                   
//                 //   array('name' => 'StripTags'),
//                 //   array('name' => 'StringTrim'),
//                ),
//                'validators' => array(
//                    array('image-file' => 'newsPicture'),
//                    array(
//                        'size'    => '',
//                        'name'    => 'StringLength',
//                        'options' => array(
//                            'encoding' => 'UTF-8',
//                            'min'      => 1,
//                            'max'      => 100,
//                        ),
//                    ),
//                ),
//            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
        
    }
}