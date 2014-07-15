<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ContactHelper extends AbstractHelper
{
    
    const my_day = 99; 
//    protected $info;
//
//    public function __invoke()
//    {
//       return $this->info;   
//    }   
//    
//    public function getInfoTable($sm, $mytablemodel)
//    {
//      //  if (!$this->info) {
//            
//            $this->info = $sm->get($mytablemodel)->getInfo(1);
//        //}
//        return $this->info;
//    }
    public function __invoke()
    {
        return 'my_day';
    }
   
}