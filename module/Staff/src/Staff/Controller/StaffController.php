<?php

namespace Staff\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Staff\Model\Staff;          // <-- Add this import
use Staff\Form\StaffForm;       // <-- Add this import

class StaffController extends AbstractActionController
{
  //  protected $aboutTable;
    
    public function indexAction() 
    {
        return new ViewModel();
    }
    
    public function personelAction()
    {
        return new ViewModel();
    }
    
   

    
}


