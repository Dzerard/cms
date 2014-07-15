<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class FileTable {
    
    //output
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    //wszystkie pliki danej oferty
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function getFile($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('fileId' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    protected $id;
    
    public function setID($id) {
        $this->id = $id;
    }
    public function getID() {
        return $this->id;
    }
    //@fix
    public function getFiles($id, $pics=false)
    {
        $id  = (int) $id;
        if($pics) {
            $rowset = $this->tableGateway->select(array('fileOfferId' => $id, 'fileType' => array('png','jpg','jpeg','gif')));
        } else {
           
             $select = new Select('file');                           
             $select->where(array('fileOfferId' => $id ));
             $select->where('fileType NOT IN ("png", "jpg", "jpeg", "gif")');
                   
             $resultSetPrototype = new ResultSet();
             
             $resultSetPrototype->setArrayObjectPrototype(new File());
             
             $paginatorAdapter = new DbSelect( $select, $this->tableGateway->getAdapter(), $resultSetPrototype );
             $rowset = new Paginator($paginatorAdapter);

//            $this->setID($id);
//            $rowset = $this->tableGateway->select(function (Select $select) {                 
//                 $select->where('fileType NOT IN ("png", "jpg", "jpeg", "gif") AND fileOfferId = '.$id.'' );                 
//            });           
        }
        return $rowset;
    }
    
    public function saveFile(File $file)
    {
        $data = array(
         
         'filePath'      => $file->filePath,
         'fileName'      => $file->fileName,
         'fileWeight'    => $file->fileWeight,
         'fileType'      => $file->fileType,
         'fileInsert'    => time(), 
         'fileOfferId'   => $file->fileOfferId,
          
        );

        $id = (int)$file->fileId;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getFile($id)) {
                unset($data['fileInsert']);
                $this->tableGateway->update($data, array('fileId' => $id));
            } else {
                throw new \Exception('File id does not exist');
            }
        }
    }
    
    public function deleteFile($id)
    {
        $this->tableGateway->delete(array('fileId' => $id));
    }
}