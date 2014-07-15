<?php

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactController extends AbstractActionController
{

    protected $infoTable;

    public function indexAction() 
    {
        try {
            $info = $this->getInfoTable()->getInfo($id=1);
        }
        catch (\Exception $ex) {
                return $this->redirect()->toRoute('home');
        }
        
        return new ViewModel(array(
            'info' => $info,
        ));
    }
    
    public function getInfoTable()
    {
        if (!$this->infoTable) {
            $sm = $this->getServiceLocator();
            $this->infoTable = $sm->get('Admin\Model\InfoTable');
        }
        return $this->infoTable;
    }
}


