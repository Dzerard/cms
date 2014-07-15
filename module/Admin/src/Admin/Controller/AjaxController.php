<?php
/**
 * Description of AjaxController
 *
 * @author Łukasz
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Zend\Json\Expr;
use Admin\Model\Contact;        //Contact
use Admin\Model\Message;        //Message
use Admin\Model\File;           //File
use Admin\Mail\Mailer;          //Sending e-mail

class AjaxController extends AbstractActionController
{
    protected $contactTable;
    protected $visualizationTable;
    protected $designTable;
    protected $offerTable;
    protected $messageTable;
    protected $fileTable;
    
    
    protected $elementType = array(
        'cube'       => 'CubeGeometry',
        'rectangle'  => 'SphereGeometry',
        'other'      => 'other' //fix
    );
    
    public function __construct() {
        //konstruktor
    }
    
    public function indexAction() {
        return;
    }
    
     //wysyłanie i dodawnie wiadmości z formularza konatktowego :)
    public function addAction() 
    {
        $response = $this->getResponse();
        $response->setStatusCode(200);
        
        $request = $this->getRequest();  
        if ($request->isXmlHttpRequest()) {
            if ($request->isPost()) {          
                
                try {
                    $contact = new Contact();              
                    $contact->exchangeArray($request->getPost());                
                    $this->getContactTable()->saveContact($contact);    
                    $response->setContent("true"); 
                    
                    // sending e-mails                
//                    $mail = new Mailer();
//                    $mail->sendContactMail($request->getPost());    
                
                } catch (Exception $e) {
                    $response->setContent("false"); 
                  //fix obsługa błędów                   
                }
            }    
        }           
        return $response;  
        
    }
   
    //wysyłanie wiadmości z formularza konatktowego dla firm 
    public function messageAction() 
    {        
        $response = $this->getResponse();
        $response->setStatusCode(200);
        
        $request = $this->getRequest();  
        if ($request->isXmlHttpRequest()) {
          
            if ($request->isPost()) {          
                
                try {
                    $message = new Message();              
                    $message->exchangeArray($request->getPost());                
                    $this->getMessageTable()->saveMessage($message);    
                    $msg = "message_ok";  
                
                } catch (Exception $e) {
                    $msg = "message_err";                   
                }  
            } 
            
            $jsonModel = new JsonModel();
            $jsonModel->setVariables(array(
              'msg'    => $msg
            ));
            
            return $jsonModel;
            
            
        } else {
            $response->setContent("no access");   
            return $response;  
        }      
    }
   
    
    public function defaultAction() {
        return;
    }
    
    public function designAction() {        
         
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $request = $this->getRequest(); 
        $id = (int) 1; //fix
        
        $design = $this->getDesignTable()->getDesign($id);  
        
        if ($request->isXmlHttpRequest()) {

            $oDesign = array();
            
            $desingFog    = unserialize($design->designFog);    //ponowne sprawdzenie a jak nie default (funkcja)
            $desingPlane  = unserialize($design->designPlane);  //ponowne sprawdzenie a jak nie default (funkcja)
            $desingLights = unserialize($design->designLights); //ponowne sprawdzenie a jak nie default (funkcja)
            
            $oDesign['fog']    = $desingFog; //fog
            $oDesign['plane']  = $desingPlane; //plane
            $oDesign['lights'] = $desingLights; //plane
            
            $value = Json::encode($oDesign);
      
            $response->setContent($value);
            $response->setStatusCode(200);
         
        }  else {
          $response->setContent('no access');   
        }
        
        return $response;
        
    }
    
    
    public function vofferAction() {
        
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $request = $this->getRequest(); 
        
        $offerId = (int) $this->params()->fromRoute('id', 0);     
       
        if ($request->isXmlHttpRequest()) {
            
            try {
                $offer = $this->getOfferTable()->getOffer($offerId);                
            } catch(Exception $e) {}
            
            $offer->offerVisible  = !($offer->offerVisible);
            
            $this->getOfferTable()->saveOffer($offer);

            $value = Json::encode(array('OK' => 'updatead_ok'));
            
            $response->setContent($value);
            $response->setStatusCode(200);
        } else {
             $response->setContent('no access'); 
        }
         return $response;
    }


    //zwaracamy wszystkie intersujace nas modele wraz z lokalizacja, wielkoscia,teksturą, logo a potem nawet z infroamcja(inna akcja)
    public function getAllElementsAction() {
      
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $request = $this->getRequest(); 
        
        $visualization = $this->getVisualizationTable()->fetchAllInfo();       
        
        if ($request->isXmlHttpRequest()) {
            
          
            $element = array();           
            
            $oCounter = 0;
            foreach($visualization as $k => $v) {      
                
                if($v->offerVisible) {                    
                
                    $element ['items'][$oCounter]['elementSize']     = $this->giveMeNiceSize($v->visualizationElementSize);   
                    $element ['items'][$oCounter]['elementScale']    = $this->giveMeNiceScale($v->visualizationElementScale);   
                    $element ['items'][$oCounter]['offerId']         = $v->visualizationOfferId;   
                    $element ['items'][$oCounter]['offerName']       = $v->offerTitle;   
                    $element ['items'][$oCounter]['offerVideo']      = $v->offerVideo;   
                    $element ['items'][$oCounter]['elementPosition'] = $this->giveMeNicePosition($v->offerNumber);
                    $element ['items'][$oCounter]['elementMaterial'] = $this->giveMeNiceColor($v->visualizationColor); 
                    $element ['items'][$oCounter]['elementType']     = $this->giveMeNiceElementType($v->visualizationElement);
                    //@todo
                    if($v->visualizationElement == 'other') {
                        $element ['items'][$oCounter]['elementCode'] = $this->giveMeNiceElementCode($v->visualizationElementCode);
                    }
                }
                $oCounter++;
            }
          
            $value = Json::encode($element);
      
            $response->setContent($value);
            $response->setStatusCode(200);
           
          
        } else {
           $response->setContent('no access'); 
        }        
        
        return $response;

    }
    
    //zapis modeli do pliku - do usunięcia 
    public function getElementCodeAction() {
      
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $request   = $this->getRequest(); 
        
        $contentId = (int) $this->params()->fromRoute('id', 0); 
        
        if($request->isXmlHttpRequest()) {          
        
            try {
                $visualization = $this->getVisualizationTable()->getVisualization($contentId, true); 
            } catch(Exception $e) {}

            if(is_object($visualization)) {
                $return = $visualization->visualizationElementCode;
            } else {
                $return = $visualization;
            }

            $value = Json::encode($return);
            
            $file = 'model.js';
            file_put_contents('public/js/models/'.$file, $return, LOCK_EX);

            $response->setContent($value);
            $response->setStatusCode(200);
            
        } else {
          $response->setContent('no access');   
        }
        
        return $response;
         
       

    }
    
    public function giveMeNiceElementType($type) {
               
        if(array_key_exists($type,$this->elementType)) {
            $oType = $this->elementType[$type];
        } else {
            $oType = null; // default
        }
               
        return $oType;
    }  
    
    public function giveMeNiceElementCode($code) {
               
        $oCode = $code; // jakies sprawdzanie 
               
        return $oCode;
    }  
    
    public function giveMeNicePosition($position) {
        
     
        $position = (int) $position;        
        $oNum     = (int) substr($position,0,1); // dla 10, 20 itd
        $oNums    = $oNum+1;
        $oNumPos  = (int) substr($position,-1);
        $zIndex   = 20;

        switch ($position) {

            case ($position < 10):       
                $oPosition = array(0 => ($oNumPos * 100) - 550, 1 => $zIndex, 2 => -400 );               
                break;
            case ($position < 20 && $position > 10):
                $oPosition = array(0 => ($oNumPos * 100) - 550, 1 => $zIndex, 2 => -200 );               
                break;
            case ($position < 30 && $position > 20):
                $oPosition = array(0 => ($oNumPos * 100) - 550, 1 => $zIndex, 2 => 0);
                break;
            case ($position < 40 && $position > 30):
                $oPosition = array(0 => ($oNumPos * 100) - 550, 1 => $zIndex, 2 => 200);
                break;
            case ($position < 50 && $position > 40):
                $oPosition = array(0 => ($oNumPos * 100) - 550, 1 => $zIndex, 2 => 400);
                break;
            default :
                
                $oPosition = array(0 => 450, 1 => $zIndex, 2 => ($oNums * 200) - 800);       
                break;          
        }
        return $oPosition;
    }

    public function giveMeNiceSize($size) {        
        
        $oSize = explode(",", $size);
        foreach ($oSize as $k => $v) {                 
            $oSize[$k] = (int) $v;
        }      
        return $oSize;
    }
    
    public function giveMeNiceScale($scale) {        
        
        $oScale = explode(",", $scale);
        foreach ($oScale as $k => $v) {                 
            $oScale[$k] = (int) $v;
        }      
        return $oScale;
    }
    
    public function giveMeNiceColor($color) {
        $oColor = $color; // psrawdzanie czy jest taki kolor
        return $oColor;
    }
            
    public function companyAction() {
        
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return array();
        }
        
        $offerId = (int) $this->params()->fromRoute('id', 0);   
        
        try {
            $offer  = $this->getOfferTable()->getOffer($offerId);                
            $files  = $this->getFileTable()->getFiles($offerId, false);   
        } catch(Exception $e) {
            //$error = true;
        } 
        
        $htmlViewPart = new ViewModel();
        
        $htmlViewPart->setTerminal(true)
                     ->setTemplate('admin/ajax/default')
                     ->setVariables(array(
                        'offer' => $offer,
                        'files' => $files
                     ));

        $htmlOutput = $this->getServiceLocator()
                           ->get('viewrenderer')
                           ->render($htmlViewPart);
        
        $jsonModel = new JsonModel();
        $jsonModel->setVariables(array(
          'html'     => $htmlOutput,
          'title'    => 'Informacje na temat firmy: <span>'.$offer->offerTitle.'</span>',         
        ));
        
        return $jsonModel;
       
        
//        
//        
//        $response = $this->getResponse();
//         
//        $offerId = (int) $this->params()->fromRoute('id', 0);   
//        
//        $viewModel = new ViewModel();
//        $viewModel->setVariables(array('info' => 'Hello', 'offerId' => $offerId))
//                  ->setTerminal(true);
//        
//        //json
//        $value = Json::encode(array('html' => $viewModel->getTemplate()));   
//        $response->setContent($value);
//        
//        return  $response;
       
    }
           
//    public function testAction() {
//        
//        $response = $this->getResponse();
//        $response->setStatusCode(200);
//        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
//        $html = $viewRender->render('/ajax/company');
//            
//        $response->setContent($value);
//        $response->setStatusCode(200);
//         return  $response;
//    }
    
    
     //Ajax
//    public function addPersonelAction() 
//    {
//         
//        $response = $this->getResponse();
//        $response->setStatusCode(200);
//        
//        $request = $this->getRequest();  
//        
//      
//            if ($request->isPost()) {        
//             
//                //new personel model
//                $personel = new Personel();                
//               
//                $nonFile = $request->getPost()->toArray(); 
//                $File = $request->getFiles()->toArray();            
//                
//                //check if file is posted
//                if($File['personelAttach']['name'] != '') {                    
//                
//                    $opis = $File['personelAttach']['name'];        
//         
//                    //validators
//                    $size = new Size(array('max'=>5242880));
//                    $extension = new Extension('txt,doc,docx,pdf,jpg,png,zip');
//
//                    $adapter = new \Zend\File\Transfer\Adapter\Http();
//                    //zmiana nazwy
//                       
//                    //maybe fix
//                    $adapter->setValidators(array($size), $File['personelAttach']['size']);
//                    $adapter->setValidators(array($extension), $File['personelAttach']);
//
//                   if(!$adapter->isValid()) {
//
//                        $response->setContent("Prawdopopodobnie plik nie spełnia wymagań ... Spróbuj jeszcze raz :)");
//
//                    }
//                    else {
//                        
//                        $fileExt = pathinfo($opis, PATHINFO_EXTENSION);               
//                        $fileName = 'personel_'.date("H_i_d_m_y").'_s'.date("s").'.'.$fileExt;
//
//                        $adapter->addFilter('Rename', './public/upload/personel/'.$fileName, $File['personelAttach']['name']);
//
//                        
//                        //if ok directory to upload
//                       $adapter->setDestination('./public/upload/personel');      
//                    
//                        
//                        if($adapter->receive($File['personelAttach']['name'])) {
//                            
//                            //$adapter->setFilters(array('name' => 'BlockCipher'));
//                          //  $adapter->addFilter($filter);
//                            $data = array_merge($nonFile, array('personelAttach' => $fileName));
//                            $personel->exchangeArray($data);  
//                            $this->getPersonelTable()->savePersonel($personel);   
//                            $response->setContent("true");
//                            
//                            // sending e-mails
//                            $data['fileType'] = $File['personelAttach']['type'];
//                            $mail = new Mailer();
//                            $mail->sendPersonelMail($data);    
//                        }
//                    }
//
//                }
//                
//                else {
//                    
//                     $data = array_merge($nonFile, array('personelAttach' => ' '));
//                    
//                     $personel->exchangeArray($data);                    
//                     $this->getPersonelTable()->savePersonel($personel);  
//                     
//                     $response->setContent("true");
//                    
//                     
//                     // sending e-mails
//                     $mail = new Mailer();
//                     $mail->sendPersonelMail($data);  
//                     
//                }
//              
//            }        
//           
//        return $response;  
//        
//    }   
//    
    
//     public function addNotificationAction() 
//    {       
//         
//        $response = $this->getResponse();
//        $response->setStatusCode(200);
//        
//        $request = $this->getRequest();  
//
//            if ($request->isPost()) {          
//
//                $notification = new Notification();  
//                                
//                $notification->exchangeArray($request->getPost());                
//                $this->getNotificationTable()->saveNotification($notification);               
//                
//                              
//                $response->setContent("true");
//                // sending e-mails
//               
//                $mail = new Mailer();
//                $mail->sendNotificationMail($request->getPost());    
//               
//                
//            }        
//           
//        return $response;  
//        
//    }
//    
    
    public function getFileTable()
    {
        if (!$this->fileTable) {
            $sm = $this->getServiceLocator();
            $this->fileTable = $sm->get('Admin\Model\FileTable');
        }
        return $this->fileTable;
    }
    
    public function getContactTable()
    {
        if (!$this->contactTable) {
            $sm = $this->getServiceLocator();
            $this->contactTable = $sm->get('Admin\Model\ContactTable');
        }
        return $this->contactTable;
    }
    
    public function getOfferTable()
    {
        if (!$this->offerTable) {
            $sm = $this->getServiceLocator();
            $this->offerTable = $sm->get('Admin\Model\OfferTable');
        }
        return $this->offerTable;
    }
    
    public function getVisualizationTable()
    {
        if (!$this->visualizationTable) {
            $sm = $this->getServiceLocator();
            $this->visualizationTable = $sm->get('Admin\Model\VisualizationTable');
        }
        return $this->visualizationTable;
    }
    
    public function getDesignTable()
    {
        if (!$this->designTable) {
            $sm = $this->getServiceLocator();
            $this->designTable = $sm->get('Admin\Model\DesignTable');
        }
        return $this->designTable;
    }
    
    public function getMessageTable()
    {
        if (!$this->messageTable) {
            $sm = $this->getServiceLocator();
            $this->messageTable = $sm->get('Admin\Model\MessageTable');
        }
        return $this->messageTable;
    }
  
}

?>
