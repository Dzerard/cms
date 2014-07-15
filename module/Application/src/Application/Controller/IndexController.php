<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;    
use Zend\Http\Header\SetCookie;
use Zend\Http\Header;
use Zend\Json\Json;
use Zend\Json\Expr;

class IndexController extends AbstractActionController
{
    //przyda sie cachowanie
    
    protected $lastNews;
    protected $lastOffer;    
    protected $langs = array(
        'PL' => 'pl',
        'UK' => 'us'
    );    
      
    //zmiana jezyka
    public function  langSwitch() {
        
        if(isset($_COOKIE['vwalkMgr'])) {
            
            $langFile = $_COOKIE['vwalkMgr'];
            $translator = $this->getServiceLocator()->get('translator');
            $translator->setLocale($langFile);
        }    
    }    
    
    //ustawianie cookies
    public function setCookies($value) {
        
        $cookie = new SetCookie('vwalkMgr', $value, time() + 30 * 60 * 60 * 24); // now + 30 days
        $response = $this->getResponse()->getHeaders();
        $response->addHeader($cookie);
    }         
    //setting cookie
    public function languageFunction($lang, $response) {
             
        if($lang) {      
            
            if(array_key_exists($lang, $this->langs)) {
                 $this->setCookies($this->langs[$lang]);
            }
            else {
                $this->setCookies('pl');
            }                        
      
            $response->setContent(Json::encode(array("OK" => 'succes_messge')));
           
         
            //return $this->redirect()->toRoute('home');
        }        
        else {
            $response->setContent(Json::encode(array('ERR' => 'error_message')));
           // return $this->redirect()->toRoute('home');
        }  
        
        $response->setStatusCode(200);        
        return $response;
    }
    
    public function testAction() 
    {
         return new ViewModel(array(
             'ok' => 'ok'
             ));
    }
    
    public function indexAction()
    {           
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $request = $this->getRequest(); 
        
        if ($request->isXmlHttpRequest()) {          
           
           return $this->languageFunction($request->getPost('langId'), $response);
        }
//        if($request->isPost()) {
//            
//            var_dump($request->getPost());
//            die;
//            $this->languageFunction($request->getPost('buttonLang'));
//            return;
//        }

        return new ViewModel(array(
            'home'  => TRUE,
           // 'news'  => $this->getNewsTable()->fetchLimit(),
           // 'offer' => $this->getOfferTable()->fetchLimit(),
        ));
    }
    
    public function walkAction() {
        
    }
    
    
    public function getNewsTable()
    {
        if (!$this->lastNews) {
            $sm = $this->getServiceLocator();
            $this->lastNews = $sm->get('Admin\Model\NewsTable');
        }
        return $this->lastNews;
    }
    
    public function getOfferTable()
    {
        if (!$this->lastOffer) {
            $sm = $this->getServiceLocator();
            $this->lastOffer = $sm->get('Admin\Model\OfferTable');
        }
        return $this->lastOffer;
    }
    
}
