<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class NotificationTable {
    
    //output
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    //wszystkie wiadomości
    public function fetchAll($paginated=false)
    {
        if($paginated) {
            
             $select = new Select('notification');  
             $select->order('notificationId DESC');
             // create a new result set based on the Notification entity
             $resultSetPrototype = new ResultSet();
             
             $resultSetPrototype->setArrayObjectPrototype(new Notification());
             
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
    
    public function getNotification($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('notificationId' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveNotification(Notification $notification)
    {
        $data = array(
           
            'notificationEmail'      => $notification->notificationEmail,            
            'notificationNote'       => $notification->notificationNote,            
            'notificationCountry'    => $notification->notificationCountry,
            'notificationInsert'     => time(),       
                      
        );

        $id = (int)$notification->notificationId;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getNotification($id)) {
                unset($data['notificationInsert']);
                $this->tableGateway->update($data, array('notificationId' => $id));
            } else {
                throw new \Exception('Notification id does not exist');
            }
        }
    }
    
    public function deleteNotification($id)
    {
        $this->tableGateway->delete(array('notificationId' => $id));
    }
}