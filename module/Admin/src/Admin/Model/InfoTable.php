<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class InfoTable {
    
    //output
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    //wiersz z inforamacjami
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getInfo($id=1)
    {
        //$id  = (int) $id;
        $id = 1;
        $rowset = $this->tableGateway->select(array('infoId' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveInfo(Info $info)
    {
        $data = array(
                            
            'infoName'      => $info->infoName,
            'infoStreet'    => $info->infoStreet,
            'infoCity'      => $info->infoCity,
            'infoPhone'     => $info->infoPhone,
            'infoCellPhone' => $info->infoCellPhone,
            'infoEmail'     => $info->infoEmail,
            'infoFax'       => $info->infoFax,
            'infoUpdate'    => time(),
           
        );

        $id = (int)$info->infoId;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getInfo($id)) {                
                $this->tableGateway->update($data, array('infoId' => $id));
            } else {
                throw new \Exception('Info id does not exist');
            }
        }
    }
    
    public function deleteInfo($id)
    {
        $this->tableGateway->delete(array('infoId' => $id));
    }
}