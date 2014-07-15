<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ContactTable {
    
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
            
             $select = new Select('contact'); 
             $select->order('contactId DESC');
             // create a new result set based on the Contact entity
             $resultSetPrototype = new ResultSet();
             
             $resultSetPrototype->setArrayObjectPrototype(new Contact());
             
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
    
    public function getContact($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('contactId' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveContact(Contact $contact)
    {
        $data = array(
            'contactName'     => $contact->contactName,
            'contactEmail'    => $contact->contactEmail,
//            'contactSubject'  => $contact->contactSubject,
            'contactPosition' => $contact->contactPosition,
//            'contactPhone'    => $contact->contactPhone,
            'contactMessage'  => $contact->contactMessage,
            'contactInsert'   => time(),
            'contactUpdate'   => time(),
        );

        $id = (int)$contact->contactId;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getContact($id)) {
                unset($data['contactInsert']);
                $this->tableGateway->update($data, array('contactId' => $id));
            } else {
                throw new \Exception('Contact id does not exist');
            }
        }
    }
    
    public function deleteContact($id)
    {
        $this->tableGateway->delete(array('contactId' => $id));
    }
}