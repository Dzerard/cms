<?php
namespace Admin\Model;

class Notification
{
    public $notificationId;
    public $notificationEmail;    
    public $notificationNote;
    public $notificationCountry;
    public $notificationInsert;
    
   
    public function exchangeArray($data)
    {
        
        $this->notificationId           = (!empty($data['notificationId'])) ? $data['notificationId'] : null;
        $this->notificationEmail        = (!empty($data['notificationEmail'])) ? $data['notificationEmail'] : null;
        $this->notificationNote         = (!empty($data['notificationNote'])) ? $data['notificationNote'] : null;
        $this->notificationCountry      = (!empty($data['notificationCountry'])) ? $data['notificationCountry'] : null;
        $this->notificationInsert       = (!empty($data['notificationInsert'])) ? $data['notificationInsert'] : null;              

    }
}