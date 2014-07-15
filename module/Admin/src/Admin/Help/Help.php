<?php

namespace Admin\Help;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class Help {
    
    public function __construct() 
    {
        
    }
    
    public static function MessageSwitcher ($message)
    {
        switch ($message) {
            case 'A record with the supplied identity could not be found.' :
                return 'Użytkownik nie został znaleziony.';
                break;  
             case 'Supplied credential is invalid.' :
                return 'Błędne hasło.';
                break;
             case 'Authentication successful.' :
                return 'Witaj. Zostałeś zalogowany do panelu administracyjnego !';
                break;
        }
        
    }
    
}
