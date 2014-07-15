<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\Partial;

use Admin\Model\Contact;        //Contact
use Admin\Model\Notification;   //Noification
use Admin\Model\News;           //News
use Admin\Model\Offer;          //Offer
use Admin\Model\User;           //User
use Admin\Model\Visualization;  //Visualization
use Admin\Model\Info;           //Info
use Admin\Model\Message;        //Message
use Admin\Model\File;           //File

use Admin\Form\NewsForm;
use Admin\Form\ViewForm;
use Admin\Form\OfferForm;
use Admin\Form\UserForm;
use Admin\Form\InfoForm;
use Admin\Form\VisualizationForm;
use Admin\Form\DesignForm;

use Admin\Help\Help;            //helpers
use Admin\Mail\Mailer;          //Sending e-mail
use Zend\Validator\File\Size;   //checkin file size
use Zend\Validator\File\Extension; //file extension

class AdminController extends AbstractActionController
{
    //odwołania do tabel w bazie
    protected $contactTable;
    protected $notificationTable;
    protected $newsTable;
    protected $offerTable;
    protected $infoTable;
    protected $visualizationTable;
    protected $designTable;
    protected $messageTable;
    protected $fileTable;
    
//    <------------------
//    auth
    protected $form;
    protected $storage;
    protected $authservice;
    
    protected $nameTab = array(         
        'date' => 'offerInsert',
        'name' => 'offerTitle',
        'desc' => 'offerDesc'                
    );
     
    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }
         
        return $this->authservice;
    }
     
    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                                  ->get('Admin\Model\MyAuthStorage');
        }
         
        return $this->storage;
    }
          
    public function authenticateAction()
    {
        $form     = new UserForm(); 
         
        $request = $this->getRequest();
        
        if ($request->isPost()){
            
            $user = new User();
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()){
                //check authentication...
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost('userName'))
                                       ->setCredential($request->getPost('userPassword'));
                                        
                $result = $this->getAuthService()->authenticate();
                
                //troche słabe te errory ...
                foreach($result->getMessages() as $message)
                {                    
                    //custom message switcher                                
                    $this->flashmessenger()->addMessage(Help::MessageSwitcher($message));
                }
                 
                if ($result->isValid()) {
                    //$redirect = 'home';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                             ->setRememberMe(1);
                        //set storage again 
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->getStorage()->write($request->getPost('userName'));                    
                    
                    return $this->redirect()->toRoute('admin', array('action' => 'settings'));
                }
            }
            
        }
        
        return $this->redirect()->toRoute('admin', array('action' => 'login'));
    }
    
    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
         
        $this->flashmessenger()->addMessage("Zostałeś wylogowany z panelu admina !");
        return $this->redirect()->toRoute('admin', array('action'=> 'login'));
    }    
    
    
//    end auth
//    ---------------------->
    public function loginAction() 
    {
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('admin');
        }
        
        $form = new UserForm();
        return array(
            'form'     => $form,
            'messages' =>  $this->flashmessenger()->getMessages()            
        );
      
    }
    
    public function indexAction() 
    { 
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        //check
       
        
        return new ViewModel();       
    }
    
   
    public function aboutAction() {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
//        $view = 
//        var_dump($this->getController());
        return new ViewModel();
    }
    
    
    public function settingsAction()
    {   
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = 1;        
        try {
            $info = $this->getInfoTable()->getInfo($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('admin', array('action' => 'settings'));
        }

        $form  = new InfoForm();
        $form->bind($info);          

        $request = $this->getRequest();        
        if ($request->isPost()) {
            
          
            //zapisywanie danych kontaktowych
            if ($request->getPost('submitInfo')) {           
                
                    $form->setInputFilter($info->getInputFilter());
                    $form->setData($request->getPost());
 
                if ($form->isValid()) {     
                    
                    // Redirect to list of albums                            
                    $this->getInfoTable()->saveInfo($info);                      
                }                                    
                
                $this->flashmessenger()->addMessage("Dane kontaktowe zostały zapisane.");
                return $this->redirect()->toRoute('admin', array('action'=>'settings'));
            }
            
            //zapisywanie plików tłumaczeń 
            if ($request->getPost('saveFiles')) {               
               
                //check if file exist and delete
                $name = $request->getPost('languageName'); //pl, eng, deu
                
                //usuwanie plików
                if($name) {
                    //*.po
                    if(is_file('./public/language/'.$name.'.po')) {
                        unlink ('./public/language/'.$name.'.po');
                    }
                    //*.mo
                     if(is_file('./public/language/'.$name.'.mo')) {
                        unlink ('./public/language/'.$name.'.mo');
                    }
                }
         
                   $File = $request->getFiles()->toArray();   
                   
                   if($File['transaltionFilePo']['name'] != '' && $File['transaltionFileMo']['name'] != '')  {    
                        
                        $poFile = $File['transaltionFilePo']['name'];  
                        $moFile = $File['transaltionFileMo']['name'];  

                        $adapter = new \Zend\File\Transfer\Adapter\Http();

                        //zmiana nazwy
                        if($File['transaltionFilePo']) {

                           $poExt = pathinfo($poFile, PATHINFO_EXTENSION);
                           $fileName = $name.'.'.$poExt;                                 
                           $adapter->addFilter('Rename', './public/language/'.$fileName, $File['transaltionFilePo']['name']);
                           if($poExt=='po') {
                                  $adapter->setDestination('./public/language/');   

                                   if($adapter->receive($File['transaltionFilePo']['name'])) {                            
                                       
                                       $statusPo = true;                                        
                                   }
                           }
                        }

                        if($File['transaltionFileMo']) {

                           $moExt = pathinfo($moFile, PATHINFO_EXTENSION);
                           $fileName = $name.'.'.$moExt;                                 
                           $adapter->addFilter('Rename', './public/language/'.$fileName, $File['transaltionFileMo']['name']);
                           if($moExt=='mo') {
                                  $adapter->setDestination('./public/language/');   

                                   if($adapter->receive($File['transaltionFileMo']['name'])) {                            

                                          $statusMo = true;                                                
                                   }
                           }
                        }                                 
                   }
                   
                   if($statusPo && $statusMo) {
                       $this->flashmessenger()->addMessage('Plik .po i plik .mo został zapisany.');
                   } 
                   else {
                       $this->flashmessenger()->addMessage('Wystapił błąd, pliki tłumaczeń nie zostały zapisane.');
                   }
                   
                }
                
            //przywracanie domyslnych plikow tlumaczen 
            if($request->getPost('defaultLang'))
            {
                //check if file exist and delete
                $name = $request->getPost('languageNameInput'); //pl, eng, deu
                
                //usuwanie plików
                if($name) {                    
                            
                    try {
                        //*.po        
                        unlink ('./public/language/'.$name.'.po');
                        if (copy('./public/language/default/'.$name.'.po', './public/language/'.$name.'.po')) {
                            $poFile = true;
                        }                    
                    
                        //*.mo                
                        unlink ('./public/language/'.$name.'.mo');
                        if (copy('./public/language/default/'.$name.'.mo', './public/language/'.$name.'.mo')) {
                            $moFile = true;
                        }                          
                    }
                    catch(\Exception $ex)     {}
                }
                
                if ($poFile && $moFile) {
                    $this->flashmessenger()->addMessage('Przywrócono domyślny język ');
                }
                else {
                    $this->flashmessenger()->addMessage('Coś poszło nie tak ...');
                }
                                
               
                
                
            }
            unset($_FILES);
            return $this->redirect()->toRoute('admin', array('action'=>'settings'));  
        }
        
        return new ViewModel(array(
          'info' => $info,
          'form' => $form,
          'messages' =>  $this->flashmessenger()->getMessages()
        )              
      );
        
       
    }
    
    public function contactAction() 
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            $paginator = $this->getContactTable()->fetchAll(true);
            $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
            $paginator->setItemCountPerPage(10);

            return new ViewModel(array(
            'paginator' => $paginator,
            'link'      => 'contact',
            ));      
      
        }
        else {
            try {
                $contact = $this->getContactTable()->getContact($id);
            }
            catch (\Exception $ex) {
                return $this->redirect()->toRoute('admin', array(
                    'action' => 'contact'
                ));
            }

            return new ViewModel(array(
                'contact' => $contact,               
                ));
        
        }
    }
    
    public function messageOfferAction() 
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0); 
        
        if($id) {
            $company    = $this->getOfferTable()->getOffer($id);
            $oCompany   = $company->offerTitle;
            $oCompanyID = $company->offerId;
        } else {
            $oCompany = " ---- ";
            $oCompanyID = '';
        }     
        
        $paginator = $this->getMessageTable()->fetchAll(true, $id);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(10);

        return new ViewModel(array(
            'paginator'   => $paginator,
            'link'        => 'contact',
            'company'     => $oCompany,            
            'companyID'   => $oCompanyID, 
            'messages'    =>  $this->flashmessenger()->getMessages()  
        ));
    }
    
    public function messageOfferShowAction() 
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0); 
        
        if($id) {
            
            try {
                $message = $this->getMessageTable()->getMessage($id);            
                $company  = $this->getOfferTable()->getOffer($message->messageOfferId);
                $companyID = $company->offerId; //jeszcze sprawdzanie uprawnień :)
                
            } catch (\Exception $ex) {
                return $this->redirect()->toRoute('admin', array(
                    'action' => 'offer'
                ));
            }
        
            return  new ViewModel(array(            
                'message'     => $message,            
                'companyID'   => $companyID,   
            ));
            
        } else {
            return $this->redirect()->toRoute('admin', array('action' => 'offer'));
        }       
    }
    
    
    public function fileAction() 
    {
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin', array(
                'action' => 'offer'
            ));
        }

        try {
            $oFiles   = $this->getFileTable()->getFiles($id, false); //dla danej firmy
            $oPics    = $this->getFileTable()->getFiles($id, true);  //obrazki
            $oOffer   = $this->getOfferTable()->getOffer($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('admin', array('action' => 'offer'));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {              

            //usuwanie zdjecia/pliku
            if ($request->getPost('del-file')) {
             
                try {
                    $fileID = $request->getPost('fileID');
                    $oFile  =  $this->getFileTable()->getFile($fileID);
                    
                    unlink ('./public/'.$oFile->filePath);
                    
                }
                catch (\Exception $ex) {
                    $this->flashmessenger()->addMessage('Błąd podczas usuwania pliku ... ');
                }
                
                $this->getFileTable()->deleteFile($fileID);
                if($request->getPost('type')) {
                    $this->flashmessenger()->addMessage('Plik został usunięty'); 
                } else {
                    $this->flashmessenger()->addMessage('Zdjęcie zostało usunięte');
                }
                
                
                return $this->redirect()->toRoute('admin', array('action'=>'file', 'id' => $id));
                      
            }  
            if($request->getPost('add-file')) {
                
                //id firmy
                $File     = $request->getFiles()->toArray();                 
                $offerId  = $request->getPost('offerId');  // offerId
                $adapter  = new \Zend\File\Transfer\Adapter\Http(); 
                
                foreach ($File as $oFile) {
                    //upload plików   
                    foreach($oFile as $file) {
                                              
                        $opis     = $file['name'];                  
                        $fileExt  = pathinfo($opis, PATHINFO_EXTENSION); //zmiana nazwy
                        $fileName = 'file_'.date("d_m_y").'_'.rand(1,999).'_'.$offerId.'.'.$fileExt;
                        $fileSize = $file['size'];
                        $filePath = 'upload/files/'.$fileName;                       
                        
                        
                        $adapter->addFilter('Rename', './public/upload/files/'.$fileName, $file['name']);                 
                                               
                        $adapter->addValidator('Size', false, array('max' => '5MB')); //maks wielkosc pliku
                
                            $adapter->setDestination('./public/upload/files');   
                            if($adapter->receive($file['name'])) {                            

                                    //save info about file
                                    $newFile = new File();   
                                    $newFile->fileType    = $fileExt;    
                                    $newFile->filePath    = $filePath;    
                                    $newFile->fileName    = $opis; //  $fileName
                                    $newFile->fileWeight  = $fileSize;
                                    $newFile->fileOfferId = $offerId;
                                    
                                    $this->getFileTable()->saveFile($newFile);
                                    $this->flashmessenger()->addMessage('Poprawnie zapisano plik: '.$opis);
                                    
                            } else {
                                $this->flashmessenger()->addMessage('<span>Problem z zapisem pliku (maks. 5MB): '.$opis.'</span>');
                            }
                    }
                }
                 // Redirect to list of files with messages
                return $this->redirect()->toRoute('admin', array('action'=>'file', 'id'=> $id));           
            }              
        } 
          
        return  new ViewModel(array(            
                'files'     => $oFiles,   
                'pics'      => $oPics,  
                'company'   => $oOffer,
                'id'        => $id,
                'messages'  => $this->flashmessenger()->getMessages() 
            ));
    }
        
    public function notificationAction() 
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            $paginator = $this->getNotificationTable()->fetchAll(true);
            $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
            $paginator->setItemCountPerPage(10);

            return new ViewModel(array(
            'paginator' => $paginator,
            'link'      => 'notification',
            ));      
      
        }
        else {
            try {
                $notification = $this->getNotificationTable()->getNotification($id);
            }
            catch (\Exception $ex) {
                return $this->redirect()->toRoute('admin', array(
                    'action' => 'notification'
                ));
            }

            return new ViewModel(array(
                'notification' => $notification,
                ));
        
        }
    }
    
    public function newsAction() 
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            $paginator = $this->getNewsTable()->fetchAll(true);
            $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
            $paginator->setItemCountPerPage(10);

            return new ViewModel(array(
            'paginator' => $paginator,
            'link'      => 'news',
            ));      
      
        }
        else {
            
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute('admin', array(
                    'action' => 'news'
                ));
            }

            try {
                $news = $this->getNewsTable()->getNews($id);
            }
            catch (\Exception $ex) {
                return $this->redirect()->toRoute('admin', array('action' => 'news'));
            }

            $form  = new NewsForm();
            $form->bind($news);
           // $form->get('submit')->setAttribute('value', 'Edit');

            $request = $this->getRequest();
            if ($request->isPost()) {
                
                //usuwanie zdjecia
                if ($request->getPost('del-picture'))
                {
                     //usuwanie zalacznika
                    if($news->newsPicture != '') {
                         unlink ('./public/upload/news/'.$news->newsPicture);
                    }
                    
                    $news->newsPicture = '';
                    $this->getNewsTable()->saveNews($news);
                    return $this->redirect()->toRoute('admin', array('action'=>'news', 'id' => $id));
                }        
                    
                //edycja newsa
                if ($request->getPost('submit')) {  
                    
                    $form->setInputFilter($news->getInputFilter());
                    $form->setData($request->getPost());

                    if ($form->isValid()) {
               
                               
                        $nonFile = $request->getPost()->toArray(); 
                        $File = $request->getFiles()->toArray();   

                        if($File['newsPicture']['name'] != '') {    

                                 //validators
                                 $size = new Size(array('max'=>5242880));
                                 //$extension = new Extension(array('jpg','png'), true);

                                 $opis = $File['newsPicture']['name'];  
                                 $adapter = new \Zend\File\Transfer\Adapter\Http();
                                //zmiana nazwy
                                 $fileExt = pathinfo($opis, PATHINFO_EXTENSION);
                                 $fileName = 'news_'.date("H_i_d_m_y").'_s'.date("s").'.'.$fileExt;
                                 $adapter->addFilter('Rename', './public/upload/news/'.$fileName, $File['newsPicture']['name']);

                                   //maybe fix
                                 $adapter->setValidators(array($size), $File['newsPicture']['size']);
                                 //$adapter->setValidators(array($extension), $fileExt);

                                 if($fileExt=='jpg' || $fileExt=='png' || $fileExt=='PNG' || $fileExt=='JPG') {

                                    $adapter->setDestination('./public/upload/news');   

                                    if($adapter->receive($File['newsPicture']['name'])) {                            

                                           $data = array_merge($nonFile, array('newsPicture' => $fileName));

                                           $news->exchangeArray($data);  
                                           $this->getNewsTable()->saveNews($news);   
                                    }

                                 return $this->redirect()->toRoute('admin', array('action'=>'news', 'id'=> $id));  

                                 }
                                 else {

                                    //$form->setMessages(array('newsPicture'=>"Prawdopopodobnie plik nie spełnia wymagań ... Spróbuj jeszcze raz :)" ));
                                    return array(
                                        'id' => $id,
                                        'form' => $form,
                                        'news' => $news,
                                    );                        
                                }

                        }
                        else {
                            
                            $this->getNewsTable()->saveNews($news);
                            // Redirect to list of albums
                            return $this->redirect()->toRoute('admin', array('action'=>'news'));
                        }
                    }
                }
            }

            return array(
                'id' => $id,
                'form' => $form,
                'news' => $news,
            );
        }
    }
        
    public function editOfferAction() {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        $id = (int) $this->params()->fromRoute('id', 0);
         
        if (!$id) {
            return $this->redirect()->toRoute('admin', array(
                'action' => 'offer'
            ));
        }

        try {
            //tutaj inne zpaytnako z joinem by sie zdało :)
            $offer = $this->getOfferTable()->getOffer($id);           
            $allLocations = $this->getOfferTable()->fetchAllLocations();
            
            //visualization jak nie ma to utworzyc ...
            $visualization = $this->getVisualizationTable()->getVisualization($id);
            
                     
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('admin', array('action' => 'offer'));
        }       
       
        //zmiany
        $form   = new OfferForm();
        $vForm  = new VisualizationForm();
        
        $form->bind($offer);       
        $vForm->bind($visualization); 
       
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            
            //save
            $data = $request->getPost()->toArray();
         
            $visualization->visualizationElement      = $data['visualizationElement'];
            $visualization->visualizationElementSize  = $data['visualizationElementSize']; 
            $visualization->visualizationElementScale = $data['visualizationElementScale'];              
            $visualization->visualizationElementCode  = $data['visualizationElementCode'];             
            $visualization->visualizationColor        = $data['visualizationColor']; 
            
            $offer->offerNumber = $data['offerNumber']; //miejscówka ;)
        
            $this->getOfferTable()->saveOffer($offer);  
            $this->getVisualizationTable()->saveVisualization($visualization); 
            
            //message
            $this->flashmessenger()->addMessage('Poprawnie zapisano.');
            //redirect
            return $this->redirect()->toRoute('admin', array('action'=>'editOffer', 'id'=> $id));
        }
       
        return array(
            'id'    => $id,        
            'offer' => $offer,
            'allLocations'  => $allLocations,      
            'visualization' => $visualization,
            'form'          => $form,
            'vForm'         => $vForm,
            'messages'      => $this->flashmessenger()->getMessages()  
        );
    }
    /**
     * @todo FIX walidatorów dla każdego uploadu :)
     * problem z walidacją i fajnie jkaby oddzielic logike walidacji do modelu ..
     * 
     */
    
    public function offerAction() 
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
              
            $request = $this->getRequest();
          
            if ($request->getQuery('s')) {
               
                $col     = $request->getQuery('c');
                $search  = $request->getQuery('s');
                if (array_key_exists($col, $this->nameTab)) {                   
                    $paginator = $this->getOfferTable()->fetchAll(true,null,$this->nameTab[$col],$search);
                } else {
                    $paginator = $this->getOfferTable()->fetchAll(true,null,'offerTitle',$search);
                }           
              
            } else if($request->getQuery()->s === '') {
                
                return $this->redirect()->toRoute('admin', array('action' => 'offer'));
               
            } else {
                 $paginator = $this->getOfferTable()->fetchAll(true);
            }
            
   
            $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
            $paginator->setItemCountPerPage(10);

            
            
            return new ViewModel(array(
            'paginator' => $paginator,
            'link'      => 'offer',
            'messages'  => $this->flashmessenger()->getMessages() 
            ));      
      
        }
        else {
            
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute('admin', array(
                    'action' => 'offer'
                ));
            }

            try {
                $offer = $this->getOfferTable()->getOffer($id);
            }
            catch (\Exception $ex) {
                return $this->redirect()->toRoute('admin', array('action' => 'offer'));
            }

            $form  = new OfferForm();
            $form->bind($offer);          

            $request = $this->getRequest();
            if ($request->isPost()) {              
       
                //usuwanie zdjecia
                if ($request->getPost('del-picture'))
                {
                     //usuwanie zalacznika
                    if($offer->offerImage != '') {
                         unlink ('./public/upload/offer/'.$offer->offerImage);
                    }
                    
                    $offer->offerImage = '';
                    $this->getOfferTable()->saveOffer($offer);
                    $this->flashmessenger()->addMessage('Zdjęcie zostało usunięte');
                    return $this->redirect()->toRoute('admin', array('action'=>'offer', 'id' => $id));
                }  
                
                //zapisywanie 
                if($request->getPost('submit')) {                    
                    
                    $form->setInputFilter($offer->getInputFilter());
                    $form->setData($request->getPost());                  
               
                    if ($form->isValid()) {
                        
                        $nonFile = $request->getPost()->toArray(); 
                        $File = $request->getFiles()->toArray(); 
                        
                        if($File['offerImage']['name'] != '') {    
                                                        
                            //validators
                            $size = new Size(array('max'=>5242880));                            

                            $opis = $File['offerImage']['name'];  
                            $adapter = new \Zend\File\Transfer\Adapter\Http();
                            
                            //zmiana nazwy
                            $fileExt = pathinfo($opis, PATHINFO_EXTENSION);
                            $fileName = 'offer_'.date("H_i_d_m_y").'_s'.date("s").'.'.$fileExt;
                            $adapter->addFilter('Rename', './public/upload/offer/'.$fileName, $File['offerImage']['name']);

                            //maybe fix
                            $adapter->setValidators(array($size), $File['offerImage']['size']);
                            
                            if($fileExt=='jpg' || $fileExt=='png' || $fileExt=='PNG' || $fileExt=='JPG') {

                                    $adapter->setDestination('./public/upload/offer');   

                                    if($adapter->receive($File['offerImage']['name'])) {                            

                                           $data = array_merge($nonFile, array('offerImage' => $fileName, 'offerSocial' => serialize($nonFile['offerSocial'])));
                                           
                                           //save
                                           $offer->exchangeArray($data);  
                                           $this->getOfferTable()->saveOffer($offer);   
                                    }

                                 return $this->redirect()->toRoute('admin', array('action'=>'offer', 'id'=> $id));  

                            }
                            else {
                                  
                                return array(
                                    'id' => $id,
                                    'form' => $form,
                                    'offer' => $offer,
                                );                        
                            }                   
                        }
                        
                        else {
                            
                            //taki myk                              
                            $data = array_merge($nonFile, array('offerSocial' => serialize($nonFile['offerSocial']))); //do walidatora                                                        
                            $offer->exchangeArray($data);                                           
                              
                            $this->getOfferTable()->saveOffer($offer);
                            $this->flashmessenger()->addMessage('Poprawnie zapisano.');
                            // Redirect to list of offers
                            return $this->redirect()->toRoute('admin', array('action'=>'offer', 'id'=> $id));
                        }
                    }     
//                    var_dump($form->getMessages());

                }  
                
            }

            return array(
                'id'       => $id,
                'form'     => $form,
                'offer'    => $offer,              
                'messages' =>  $this->flashmessenger()->getMessages()  
            );
            
        }
    }   
    
    public function viewAction() 
    {       
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
                    
        $id = (int) 1; //static id (fix)
        
        try {
            $design = $this->getDesignTable()->getDesign($id);            
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('admin', array('action' => 'settings'));
        }   
        
        $designFog    = unserialize($design->designFog);
        $designPlane  = unserialize($design->designPlane);
        $designLights = unserialize($design->designLights);
        
        $form   = new DesignForm();  
        //$form->bind($design);    
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $data = $request->getPost()->toArray();
            
            $design->designFog       = (empty($data['designFog']['on']) ? null : serialize($data['designFog'])); //do funkcji
            $design->designPlane     = (empty($data['designPlane']['param1']) ? null : serialize($data['designPlane'])); //do funkcji
            $design->designLights    = $this->fixLights($data['designLights']); 

//            
//            $offer->offerNumber = $data['offerNumber']; //miejscówka ;)
//        
            $this->getDesignTable()->saveDesign($design);  
//           
            
            //message
            $this->flashmessenger()->addMessage('Poprawnie zapisano.');
            //redirect
            return $this->redirect()->toRoute('admin', array('action'=>'view'));
        }   
         
        return array(
            'js'           => '<script> resetActions();</script>',
            'form'         => $form,
            'design'       => $design,
            'designFog'    => $designFog,
            'designPlane'  => $designPlane,            
            'designLights' => $designLights,  
            'messages'     => $this->flashmessenger()->getMessages()  
        ); 
    }
    
    public function fixLights($lights) {
        
        $oLights = array();
        
        if(empty($lights)) {
            $oDefault = array('type'=>'gLight','param1'=>'1','param2' => '1', 'param3' => '1', 'color' => '#ffffff');
            return $oDefault;
        } else {
            $count = 1;
            foreach($lights as $k=> $v) {
                $oLights[$count]['type']   = $v['type'];
                $oLights[$count]['param1'] = $v['param1'];
                $oLights[$count]['param2'] = $v['param2'];
                $oLights[$count]['param3'] = $v['param3'];
                $oLights[$count]['color']  = $v['color'];            
                $count++;
            }
        }      
        
        return serialize($oLights);
    }
    
    //dodawanie newsow
    public function addNewsAction()
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $form = new NewsForm();
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $news = new News();
     
            $form->setInputFilter($news->getInputFilter());
            $form->setData($request->getPost());                         
                     
            if ($form->isValid()) {                
                   
                    $nonFile = $request->getPost()->toArray(); 
                    $File = $request->getFiles()->toArray();   
                    
                    if($File['newsPicture']['name'] != '') {    
                             
                             //validators
                             $size = new Size(array('max'=>5242880));
                             //$extension = new Extension(array('jpg','png'), true);

                             $opis = $File['newsPicture']['name'];  
                             $adapter = new \Zend\File\Transfer\Adapter\Http();
                            //zmiana nazwy
                             $fileExt = pathinfo($opis, PATHINFO_EXTENSION);
                             $fileName = 'news_'.date("H_i_d_m_y").'_s'.date("s").'.'.$fileExt;
                             $adapter->addFilter('Rename', './public/upload/news/'.$fileName, $File['newsPicture']['name']);

                               //maybe fix
                             $adapter->setValidators(array($size), $File['newsPicture']['size']);
                             //$adapter->setValidators(array($extension), $fileExt);
                             
                            if($fileExt=='jpg' || $fileExt=='png' || $fileExt=='PNG' || $fileExt=='JPG') {
                                 
                                $adapter->setDestination('./public/upload/news');   

                                if($adapter->receive($File['newsPicture']['name'])) {                            

                                       $data = array_merge($nonFile, array('newsPicture' => $fileName));

                                       $news->exchangeArray($data);  
                                       $this->getNewsTable()->saveNews($news);   
                                }
                                
                             return $this->redirect()->toRoute('admin', array('action'=>'news'));  
                               
                             }
                             else {
                                 
                                //$form->setMessages(array('newsPicture'=>"Prawdopopodobnie plik nie spełnia wymagań ... Spróbuj jeszcze raz :)" ));
                                return array('form' => $form);                          
                            }
                            
                    }
                    
                    else {
                        
                        $news->exchangeArray($form->getData());
                        $this->getNewsTable()->saveNews($news);
                        
                        
                        return $this->redirect()->toRoute('admin', array('action'=>'news'));                
                    }
            }
        }
        return array('form' => $form);
              
    }
    
    //dodawanie ofert
    public function addOfferAction()
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        $form = new OfferForm();        
     //   $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            $offer = new Offer();
     
            $form->setInputFilter($offer->getInputFilter());
            $form->setData($request->getPost());
     
                         
            if ($form->isValid()) {
                
                $offer->exchangeArray($form->getData());
                $this->getOfferTable()->saveOffer($offer);
                
                $this->getVisualizationTable()->saveVisualizationDefault($this->getOfferTable()->lastInsertID()); //default save 
                
                $this->flashmessenger()->addMessage("Firma została zapisana.");
                // Redirect to list of albums
                return $this->redirect()->toRoute('admin', array('action'=>'offer'));
            }
//            var_dump($request->getPost());
//            var_dump($form->getMessages());
            
        }
        return array('form' => $form);
    }
    
    
    //    <----------------- Ajax teraz w oddzielnym kontrolerze---------------------->
      
    //akcje do usuwania
    
    public function messageOfferDelAction()
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $del = $request->getPost('del');
            if ($del == 'Usuń') {
                $id = (int) $request->getPost('id');
                $this->getMessageTable()->deleteMessage($id);
            

                // Redirect to list of albums
                $this->flashmessenger()->addMessage('Wiadomość została usunięta');
                return $this->redirect()->toRoute('admin', array(
                    'action' => 'message-offer', 'id' => $request->getPost('offerID')
                ));
            } else {
                return $this->redirect()->toRoute('admin', array(
                    'action' => 'offer'
                ));
            }
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('action' => 'offer'));
        }
        
        $message = $this->getMessageTable()->getMessage($id);
        $companyID = $message->messageOfferId; // sprawdzanie czy uzytkownik ma pozwolenie
        
        return array(
            'companyID' => $companyID,
            'message'   => $message,            
        );
        
    }
    
    public function delContactAction()
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('action' => 'contact'));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Powrót');
            
            if ($del == 'Usuń') {
                $id = (int) $request->getPost('id');
                $this->getContactTable()->deleteContact($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('admin', array(
                'action' => 'contact'
            ));
        }

        return array(
            'id'    => $id,
            'contact' => $this->getContactTable()->getContact($id)
        );
        
    }
    
    public function delNotificationAction()
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('action' => 'notification'));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Powrót');
            
            if ($del == 'Usuń') {
                $id = (int) $request->getPost('id');
                $this->getNotificationTable()->deleteNotification($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('admin', array(
                'action' => 'notification'
            ));
        }

        return array(
            'id'    => $id,
            'notification' => $this->getNotificationTable()->getNotification($id)
        );
        
    }    
     
    public function delNewsAction()
    {
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('action' => 'news'));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Powrót');
            
            if ($del == 'Usuń') {
                $id = (int) $request->getPost('id');
                
                
            try {
                $news = $this->getNewsTable()->getNews($id);
            
                //usuwanie zalacznika
                if($news->newsPicture != '') {
                    unlink ('./public/upload/news/'.$news->newsPicture);
                }
         
              }
              catch (\Exception $ex) {             } 
              
                //usuwanie rekordu
                $this->getNewsTable()->deleteNews($id);
                
               
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('admin', array(
                'action' => 'news'
            ));
        }

        return array(
            'id'    => $id,
            'news' => $this->getNewsTable()->getNews($id)
        );
        
    }
    
    public function delOfferAction()
    {   
        
//        // Get the "layout" view model and set an alternate template
//        $layout = $this->layout();
//        $layout->setTemplate('article/layout');

//        // Create and return a view model for the retrieved article
//        $view = new ViewModel(array('article' => $article));
//        $view->setTemplate('content/article');
//        return $view;
        
        
        //check
        if (! $this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin', array('action' => 'login'));
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('admin', array('action' => 'offer'));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Powrót');
            
            if ($del == 'Usuń') {
                $id = (int) $request->getPost('id');
                
                
                try {
                    $offer = $this->getOfferTable()->getOffer($id);

                    //usuwanie zalacznika
                    if($offer->offerImage != '') {
                        unlink ('./public/upload/offer/'.$offer->offerImage);
                    }
         
                }
                catch (\Exception $ex) {             } 

                //Message & usuwanie rekordu
                $this->flashmessenger()->addMessage('Firma została usunięta');
                $this->getOfferTable()->deleteOffer($id);               
            }
           
            return $this->redirect()->toRoute('admin', array(
                'action' => 'offer',              
            ));
        }
        //content
        $viewContent = $this->getOfferTable()->getOffer($id);

        $view = new ViewModel(array('id'=> $id,
                                    'viewContentId'  => $viewContent->offerId,
                                    'action' => 'delOffer',
                                    'back'   => 'offer',
                                    'icon'   => 'users',
                                    'title'  => $viewContent->offerTitle,
                                    'name'   => 'Firmy'
        ));
        $view->setTemplate('partial/delete');
        return $view;
        
    }
    
    public function getFileTable()
    {
        if (!$this->fileTable) {
            $sm = $this->getServiceLocator();
            $this->fileTable = $sm->get('Admin\Model\FileTable');
        }
        return $this->fileTable;
    }
    
    public function getMessageTable()
    {
        if (!$this->messageTable) {
            $sm = $this->getServiceLocator();
            $this->messageTable = $sm->get('Admin\Model\MessageTable');
        }
        return $this->messageTable;
    }
    
    public function getContactTable()
    {
        if (!$this->contactTable) {
            $sm = $this->getServiceLocator();
            $this->contactTable = $sm->get('Admin\Model\ContactTable');
        }
        return $this->contactTable;
    }
    
    public function getNotificationTable()
    {
        if (!$this->notificationTable) {
            $sm = $this->getServiceLocator();
            $this->notificationTable = $sm->get('Admin\Model\NotificationTable');
        }
        return $this->notificationTable;
    }    
    
    public function getNewsTable()
    {
        if (!$this->newsTable) {
            $sm = $this->getServiceLocator();
            $this->newsTable = $sm->get('Admin\Model\NewsTable');
        }
        return $this->newsTable;
    }
    
    public function getOfferTable()
    {
        if (!$this->offerTable) {
            $sm = $this->getServiceLocator();
            $this->offerTable = $sm->get('Admin\Model\OfferTable');
        }
        return $this->offerTable;
    }
    
    public function getInfoTable()
    {
        if (!$this->infoTable) {
            $sm = $this->getServiceLocator();
            $this->infoTable = $sm->get('Admin\Model\InfoTable');
        }
        return $this->infoTable;
    }
    
    public function getDesignTable()
    {
        if (!$this->designTable) {
            $sm = $this->getServiceLocator();
            $this->designTable = $sm->get('Admin\Model\DesignTable');
        }
        return $this->designTable;
    }
    
    public function getVisualizationTable()
    {
        if (!$this->visualizationTable) {
            $sm = $this->getServiceLocator();
            $this->visualizationTable = $sm->get('Admin\Model\VisualizationTable');
        }
        return $this->visualizationTable;
    }
    
    
    public function serviceMailAction() {
               
        $request = $this->getRequest();  

        if ($request->isPost()) {          
            //przydałaby się walidacja
            //pustych maili nie przyjmuje ;)
            if($request->getPost('emailTitle') != '' && $request->getPost('emailDesc') != '') {
                try {
                    // sending e-mails
                    $mail = new Mailer();
                    $mail->sendServiceMail($request->getPost());                   
                    //custom message switcher                                
                    $this->flashmessenger()->addMessage('Wiadomość została wysłana');
                }
                catch(\Exception $e) {
                    $this->flashmessenger()->addMessage('Niestety wystapił błąd podczas wysyłania - spróbuj jeszcze raz.');
                }
             
                return $this->redirect()->toRoute('admin', array('action' => 'settings'));         
            }
            else {
                return $this->redirect()->toRoute('admin');
            }              
        }        
        
        return $this->redirect()->toRoute('admin'); 
    }   
}


