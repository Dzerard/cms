<?php
namespace Admin\Model;

class Contact
{
    public $contactId;
    public $contactName;
    public $contactEmail;
//    public $contactSubject;
    public $contactPosition;
//    public $contactPhone;
    public $contactMessage;
    public $contactInsert;
    public $contactUpdate;
   
    public function exchangeArray($data)
    {
        $this->contactId         = (!empty($data['contactId'])) ? $data['contactId'] : null;
        $this->contactName       = (!empty($data['contactName'])) ? $data['contactName'] : null;
        $this->contactEmail      = (!empty($data['contactEmail'])) ? $data['contactEmail'] : null;
//        $this->contactSubject    = (!empty($data['contactSubject'])) ? $data['contactSubject'] : null;
        $this->contactPosition   = (!empty($data['contactPosition'])) ? $data['contactPosition'] : null;
//        $this->contactPhone      = (!empty($data['contactPhone'])) ? $data['contactPhone'] : null;
        $this->contactMessage    = (!empty($data['contactMessage'])) ? $data['contactMessage'] : null;
        $this->contactInsert     = (!empty($data['contactInsert'])) ? $data['contactInsert'] : null;
        $this->contactUpdate     = (!empty($data['contactUpdate'])) ? $data['contactUpdate'] : null;

    }
}