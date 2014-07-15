<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;


class DesignTable {
       
    //output
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;      
    }
       
    public function getDesign($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('designId' => $id));
        $row = $rowset->current();
        if (!$row) {
           throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveDesign(Design $design)
    {
        $data = array(
        
           'designFog'            => $design->designFog,
           'designLights'         => $design->designLights,
           'designPlane'          => $design->designPlane,
           'designUpdate'         => time(),      
        );

        $id = (int)$design->designId;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getDesign($id)) {                
                $this->tableGateway->update($data, array('designId' => $id));
            } else {
                throw new \Exception('Design id does not exist');
            }
        }
    }

}