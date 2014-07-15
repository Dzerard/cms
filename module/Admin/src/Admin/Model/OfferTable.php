<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class OfferTable {
    
    //output
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    //wszystkie wiadomości
    public function fetchAll($paginated=false, $sort=null, $column=null, $search=null)
    {
        if($paginated) {
            
             $select = new Select('offer');  
             $select->order('offerId DESC');
             if($sort) {
                  $select->where(array('offerCategory' => $sort ));
             }
             if($column && $search) {
                   
                   $select->where("$column LIKE '%$search%'");
                   
             }
             // create a new result set based on the Offer entity
             $resultSetPrototype = new ResultSet();
             
             $resultSetPrototype->setArrayObjectPrototype(new Offer());
             
             // create a new pagination adapter object
             $paginatorAdapter = new DbSelect(
                 // our configured select object
                 $select,
                 // the adapter to run it against
                 $this->tableGateway->getAdapter(),
                 // the result set to hydrate
                 $resultSetPrototype
             );
             $paginator = new Paginator($paginatorAdapter);
             return $paginator;
         }
        

        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function fetchAllLocations() {
        
        $adapter = $this->tableGateway->getAdapter();
        
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('offer')->columns(array('position' => 'offerNumber','name' =>'offerTitle','id' => 'offerId'));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();
        
        return $resultSet;    
    }
    
    public function fetchLimit()
    {
       $resultSet = $this->tableGateway->select(function (Select $select) {
            $select->order('offerId DESC');
            $select->limit(3);
       });
       return $resultSet;            
        
    }
    
     public function lastInsertID() {
         $id = $this->tableGateway->getLastInsertValue(); 
         return $id;
     }
    
    public function getOffer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('offerId' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveOffer(Offer $offer)
    {
        $data = array(

            'offerTitle'        => $offer->offerTitle,
            'offerDesc'         => $offer->offerDesc,
            'offerExtraInfo'    => $offer->offerExtraInfo,
            'offerType'         => $offer->offerType,
            'offerCountry'      => $offer->offerCountry, 
            'offerCity'         => $offer->offerCity,
            'offerStreet'       => $offer->offerStreet,
            'offerNumber'       => $offer->offerNumber,
            'offerCategory'     => $offer->offerCategory,
            'offerWebPage'      => $offer->offerWebPage,                    
            'offerEmail'        => $offer->offerEmail,  
            'offerPhone'        => $offer->offerPhone,  
            'offerImage'        => $offer->offerImage,
            'offerVideo'        => $offer->offerVideo,
            'offerSocial'       => $offer->offerSocial,
            'offerVisible'      => $offer->offerVisible,
            'offerInsert'       => time(),    
                    
          
        );

        $id = (int)$offer->offerId;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOffer($id)) {
                unset($data['offerInsert']);
                $this->tableGateway->update($data, array('offerId' => $id));
            } else {
                throw new \Exception('Offer id does not exist');
            }
        }
    }
    
    public function deleteOffer($id)
    {
        $this->tableGateway->delete(array('offerId' => $id));
    }
}