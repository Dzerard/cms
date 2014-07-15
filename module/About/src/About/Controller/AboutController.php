<?php

namespace About\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use About\Model\About;          // <-- Add this import
use About\Form\AboutForm;       // <-- Add this import

class AboutController extends AbstractActionController
{
  //  protected $aboutTable;
    
    public function indexAction() 
    {
        return new ViewModel();
    }
    
    public function teamAction()
    {
        return new ViewModel();        
    }
    
    public function careerAction()
    {
        return new ViewModel();      
    }

    public function mediaAction()
    {
        return new ViewModel();
    }
    
    
    
}


