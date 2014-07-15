<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Visualization
{
    public $visualizationId;
    public $visualizationOfferId;    
    public $visualizationElement;
    public $visualizationElementSize;
    public $visualizationElementScale;
    public $visualizationElementCode;
    public $visualizationElementFile;    
    public $visualizationColor;
    public $visualizationInsert;
    public $visualizationUpdate;
    
    protected $inputFilter;

   
    public function exchangeArray($data)
    {
        
        $this->visualizationId            = (!empty($data['visualizationId'])) ? $data['visualizationId'] : null;
        $this->visualizationOfferId       = (!empty($data['visualizationOfferId'])) ? $data['visualizationOfferId'] : null;
        $this->visualizationElement       = (!empty($data['visualizationElement'])) ? $data['visualizationElement'] : null;
        $this->visualizationElementSize   = (!empty($data['visualizationElementSize'])) ? $data['visualizationElementSize'] : null; 
        $this->visualizationElementScale  = (!empty($data['visualizationElementScale'])) ? $data['visualizationElementScale'] : null; 
        $this->visualizationElementCode   = (!empty($data['visualizationElementCode'])) ? $data['visualizationElementCode'] : null; 
        $this->visualizationElementFile   = (!empty($data['visualizationElementFile'])) ? $data['visualizationElementFile'] : null; 
        $this->visualizationColor         = (!empty($data['visualizationColor'])) ? $data['visualizationColor'] : null;    
        $this->visualizationInsert        = (!empty($data['visualizationInsert'])) ? $data['visualizationInsert'] : null;
        $this->visualizationUpdate        = (!empty($data['visualizationUpdate'])) ? $data['visualizationUpdate'] : null;   
        //fix
        $this->offerId         = (!empty($data['offerId'])) ? $data['offerId'] : null;
        $this->offerTitle      = (!empty($data['offerTitle'])) ? $data['offerTitle'] : null;
        $this->offerDesc       = (!empty($data['offerDesc'])) ? $data['offerDesc'] : null;
        $this->offerExtraInfo  = (!empty($data['offerExtraInfo'])) ? $data['offerExtraInfo'] : null;
        $this->offerType       = (!empty($data['offerType'])) ? $data['offerType'] : null;
        $this->offerCountry    = (!empty($data['offerCountry'])) ? $data['offerCountry'] : null;              
        $this->offerCity       = (!empty($data['offerCity'])) ? $data['offerCity'] : null;
        $this->offerStreet     = (!empty($data['offerStreet'])) ? $data['offerStreet'] : null;
        $this->offerNumber     = (!empty($data['offerNumber'])) ? $data['offerNumber'] : null;
        $this->offerCategory   = (!empty($data['offerCategory'])) ? $data['offerCategory'] : null;
        $this->offerWebPage    = (!empty($data['offerWebPage'])) ? $data['offerWebPage'] : null;
        $this->offerEmail      = (!empty($data['offerEmail'])) ? $data['offerEmail'] : null;
        $this->offerPhone      = (!empty($data['offerPhone'])) ? $data['offerPhone'] : null;
        $this->offerVisible    = (!empty($data['offerVisible'])) ? $data['offerVisible'] : null;
        $this->offerImage      = (!empty($data['offerImage'])) ? $data['offerImage'] : null;
        $this->offerInsert     = (!empty($data['offerInsert'])) ? $data['offerInsert'] : null;  
        $this->offerVideo      = (!empty($data['offerVideo'])) ? $data['offerVideo'] : null;
    }
  
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    /**
     * 
     * @todo 
     * reszta walidacji
     */
    
//    public function getInputFilter()
//    {
//        if (!$this->inputFilter) {
//            
//            $inputFilter = new InputFilter();
//
////            $inputFilter->add(array(
////                'name'     => 'offerId',
////                'required' => true,
////                'filters'  => array(
////                    array('name' => 'Int'),
////                ),
////            ));          
//          
////            
////            dodac walidacje pliku
////            $inputFilter->add(array(
////                'name'     => 'offerImage',
////                'required' => false,
////               
////            ));
//
//            $this->inputFilter = $inputFilter;
//        }
//
//        return $this->inputFilter;
//        
//    }
}