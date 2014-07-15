<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Json\Json;
class VisualizationTable {
       
    //output
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;      
    }
    
    //wszystkie wiadomości
    public function fetchAll()
    {       
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    //wszystkie wiadomosci z joinem ;)
    public function fetchAllInfo() {
                       
        $select = $this->tableGateway->getSql()
                ->select()
                ->join('offer', 'visualization.visualizationOfferId=offer.offerId');
  
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;               
    }
    
    public function getVisualization($id, $json=false)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('visualizationOfferId' => $id));
        $row = $rowset->current();
        if (!$row) {
            if($json) {
                return array('ERR' => "Could not find row $id");
            } else {
                throw new \Exception(Json::encode(array('ERR' => "Could not find row $id")));
            }        
        }
        return $row;
    }
    
    public function saveVisualization(Visualization $visualization)
    {
        $data = array(
        
           'visualizationOfferId'        => $visualization->visualizationOfferId,
           'visualizationElement'        => $visualization->visualizationElement,
           'visualizationElementSize'    => $visualization->visualizationElementSize,
           'visualizationElementScale'   => $visualization->visualizationElementScale,
           'visualizationElementCode'    => $visualization->visualizationElementCode,
           'visualizationElementFile'    => $visualization->visualizationElementFile,
           'visualizationColor'          => $visualization->visualizationColor,                 
           'visualizationInsert'         => time(),
           'visualizationUpdate'         => time(),                  
          
        );

        $id = (int)$visualization->visualizationOfferId;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getVisualization($id)) {
                unset($data['visualizationInsert']);
                $this->tableGateway->update($data, array('visualizationOfferId' => $id));
            } else {
                throw new \Exception('Visualization id does not exist');
            }
        }
    }
    
    public function saveVisualizationDefault($id) {
        
         $data = array(
        
           'visualizationOfferId'        => $id,
           'visualizationElement'        => 'cube',
           'visualizationElementSize'    => '1,1,1',
//             'visualizationElementScale' => '1,1,1',
           'visualizationColor'          => '#000000',        
           'visualizationInsert'         => time(),
           'visualizationUpdate'         => time(),                  
          
        );
        
        $this->tableGateway->insert($data);
        
    }
    
    
    public function deleteVisualization($id)
    {
         $this->tableGateway->delete(array('visualizationOfferId' => $id));
    }
}