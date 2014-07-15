<?php
namespace Admin\Model;

class Message
{
    public $messageId;
    public $messageName;
    public $messageTitle;
    public $messageEmail;
    public $messageText;
    public $messageOfferId;
    public $messageInsert;
    
   
    public function exchangeArray($data)
    {
        $this->messageId       = (!empty($data['messageId'])) ? $data['messageId'] : null;
        $this->messageName     = (!empty($data['messageName'])) ? $data['messageName'] : null;
        $this->messageTitle    = (!empty($data['messageTitle'])) ? $data['messageTitle'] : null;
        $this->messageEmail    = (!empty($data['messageEmail'])) ? $data['messageEmail'] : null;
        $this->messageText     = (!empty($data['messageText'])) ? $data['messageText'] : null;
        $this->messageOfferId  = (!empty($data['messageOfferId'])) ? $data['messageOfferId'] : null;
        $this->messageInsert   = (!empty($data['messageInsert'])) ? $data['messageInsert'] : null;

    }
}