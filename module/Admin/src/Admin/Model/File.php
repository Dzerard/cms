<?php
namespace Admin\Model;

class File
{
    public $fileId;
    public $filePath;
    public $fileName;
    public $fileWeight;
    public $fileType;
    public $fileInsert;
    public $fileOfferId;
   
    public function exchangeArray($data)
    {
        $this->fileId         = (!empty($data['fileId'])) ? $data['fileId'] : null;
        $this->filePath       = (!empty($data['filePath'])) ? $data['filePath'] : null;
        $this->fileName       = (!empty($data['fileName'])) ? $data['fileName'] : null;
        $this->fileWeight     = (!empty($data['fileWeight'])) ? $data['fileWeight'] : null;
        $this->fileType       = (!empty($data['fileType'])) ? $data['fileType'] : null;
        $this->fileInsert     = (!empty($data['fileInsert'])) ? $data['fileInsert'] : null;
        $this->fileOfferId    = (!empty($data['fileOfferId'])) ? $data['fileOfferId'] : null;

    }
}