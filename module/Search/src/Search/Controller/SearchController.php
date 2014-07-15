<?php

namespace Search\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Search\Model\Search;          // <-- Add this import
use Search\Form\SearchForm;       // <-- Add this import
use Zend\Http\PhpEnvironment\RemoteAddress;

class SearchController extends AbstractActionController
{
  //  protected $aboutTable;
    
    public function indexAction() 
    {   

        //przekzywanie adresu
        //$uri = $this->getRequest()->getUri();

        return new ViewModel();
    }
    
    public function sitemapAction()
    {
        return new ViewModel();
        
    }
    
    
   

    
}


