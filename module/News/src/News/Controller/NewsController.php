<?php

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\NewsForm;       // <-- Add this import
use Zend\Http\PhpEnvironment\RemoteAddress;

class NewsController extends AbstractActionController
{
    protected $newsTable;
    
    public function indexAction() 
    {         
        //przekzywanie adresu
        //$uri = $this->getRequest()->getUri();
        
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
                return $this->redirect()->toRoute('news');
            }

            try {
                $news = $this->getNewsTable()->getNews($id);
            }
            catch (\Exception $ex) {
                return $this->redirect()->toRoute('news');
            }

            return new ViewModel(array(                
                'news' => $news
            ));
        }

        return new ViewModel();
    }
    
    public function getNewsTable()
    {
        if (!$this->newsTable) {
            $sm = $this->getServiceLocator();
            $this->newsTable = $sm->get('Admin\Model\NewsTable');
        }
        return $this->newsTable;
    }
       
   

    
}


