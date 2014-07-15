<?php

namespace Candidates\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form;

class CandidatesController extends AbstractActionController
{
    protected $popularOffer;
    protected $allOffer;  
    
    public function indexAction() 
    {
        return new ViewModel();
    }
    
    public function applyAction()
    {
        return new ViewModel();        
    }
    
    public function offerAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        //param
        $param = $this->params()->fromRoute('category');
        //categories
        $categories = Form\OfferForm::getCategories();
        
        if (!$id) {
            
            //trzeba dorobic madre sprawdzanie
            $category = $this->params()->fromRoute('category');
            //$categoryCheck = CandidatesController::checkCategory($category);
            
            if($category) {
                
                $paginator = $this->getOfferTable()->fetchAll(true, $category);
                $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
                $paginator->setItemCountPerPage(10);
                
                return new ViewModel(array(
                    'paginator' => $paginator,
                    'link'      => 'candidates',
                    'param'     => $param,
                    'categories' => $categories,
                ));  
            }
            
            
            $paginator = $this->getOfferTable()->fetchAll(true);
            $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
            $paginator->setItemCountPerPage(10);

            
           
            return new ViewModel(array(
                'paginator' => $paginator,
                'link'      => 'candidates',
                'param'     => $param,
                'categories' => $categories,
            ));      
      
        }
        else {
            
          
            if (!$id) {
                return $this->redirect()->toRoute('candidates', array('action' => 'offer'));
            }

            try {
                $offer = $this->getOfferTable()->getOffer($id);
            }
            catch (\Exception $ex) {
                return $this->redirect()->toRoute('candidates', array('action' => 'offer'));
            }
            
            if($offer) {
                
                $images = CandidatesController::imagesByCategory($offer->offerImage, $offer->offerCategory);   
                $categories = Form\OfferForm::getCategories();
            }

            return new ViewModel(array(                
                'offer'      => $offer,
                'images'     => $images,
                'categories' => $categories,
                
            ));
        }

        return new ViewModel();
    }
    
    public function recommendAction()
    {
        return new ViewModel();        
    }
    public function departureAction()
    {
        return new ViewModel();        
    }
    
    public function referenceAction() 
    {
        return new ViewModel();
    }

    
    public function getOfferTable()
    {
        if (!$this->allOffer) {
            $sm = $this->getServiceLocator();
            $this->allOffer = $sm->get('Admin\Model\OfferTable');
        }
        return $this->allOffer;
    }
    
    public static function imagesByCategory($image, $category)
    {
        
        $sliderImages = array(
            
            'automotive'     =>  array(
                '0'    => 'automotive.jpg',
                '1'    => 'automotive_2.jpg',
            ),
            'office'         => 'office.jpg',
            'budownictwo'    => 'budownictwo.jpg',        
            'gastronomia'    => 'gastronomia.jpg',
            'hr_kadry'       => 'rekrutacja.jpg',
            'telekom'        => 'telekom.jpg',
            'telemark'       => 'telemarketing.jpg',
            'turystyka'      => 'turystyka.jpg',
            'tworzywa'       => 'tworzywa.jpg',
            'energetyka'     => 'energetyka.jpg',
            'elektryka'      => 'elektryka.jpg',
            'informatyka'    => 'informatyka.jpg',
            'logistyka'      => 'spedycja.jpg',
            'media'          => 'social_media.jpg',
            'medycyna'       => 'doctor.jpg',
            'motoryzacja'    => 'automotive.jpg',
            'opieka'         => 'opieka.jpg',
            'poligrafia'     => 'poligrafia.jpg',
            'produkcja'      => 'produkcja.jpg',
            'spedycja'       => 'spedycja.jpg',        
            'pomocdom'       => 'clean.jpg',  
            'default'        =>  array(
                '0'    => 'opieka.jpg',
                '1'    => 'automotive.jpg',
                '2'    => 'doctor.jpg',
                '3'    => 'elektryka.jpg',
                '4'    => 'logistic.jpg',
                '5'    => 'telemarketing.jpg',
            ),
        );
        
        
        if($image != '') { 
            
            $customImages[] = 'upload/offer/'.$image;            
        }
        
        else {
            if(count($sliderImages[$category]) > 1) {
                
                $customImages = $sliderImages[$category];                
                foreach ($customImages as $k=>$v) {
                    $help[$k] = 'images/placeholders/default/'.$v;
                }
                unset($customImages);
                $customImages = $help;
            }
            else {
                $customImages[] = 'images/placeholders/default/'.$sliderImages[$category];
            }
            
        }
        
        return $customImages;
        
    }
}


