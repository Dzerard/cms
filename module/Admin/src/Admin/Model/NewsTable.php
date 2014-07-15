<?php
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class NewsTable {
    
    //output
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    //wszystkie wiadomości
    public function fetchAll($paginated=false, $limit=false)
    {
        if($paginated) {
            
             $select = new Select('news');  
             $select->order('newsId DESC');
            
             // create a new result set based on the News entity
             $resultSetPrototype = new ResultSet();
             
             $resultSetPrototype->setArrayObjectPrototype(new News());
             
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
         else if($limit) {
             
             $select = new Select('news');  
             $select->order('newsId DESC');
             $select->limit(3);

             $resultSetPrototype = new ResultSet();             
             $resultSetPrototype->setArrayObjectPrototype(new News());
             
             $paginatorAdapter = new DbSelect(
                 // our configured select object
                 $select,
                 // the adapter to run it against
                 $this->tableGateway->getAdapter(),
                 // the result set to hydrate
                 $resultSetPrototype
             );
             
             $limited = new Paginator($paginatorAdapter);
             return $limited;
            
            // return $lastNews;
         }

        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function fetchLimit()
    {
       $resultSet = $this->tableGateway->select(function (Select $select) {
            $select->order('newsId DESC');
            $select->limit(4);
       });
       return $resultSet;
            
        
    }
    
    public function getNews($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('newsId' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveNews(News $news)
    {
        $data = array(
            
            'newsTitlePl'     => $news->newsTitlePl,            
            'newsMessagePl'   => $news->newsMessagePl,           
            'newsPicture'     => $news->newsPicture,
            'newsInsert'      => time(),            
          
        );

        $id = (int)$news->newsId;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getNews($id)) {
                unset($data['newsInsert']);
                $this->tableGateway->update($data, array('newsId' => $id));
            } else {
                throw new \Exception('News id does not exist');
            }
        }
    }
    
    public function deleteNews($id)
    {
        $this->tableGateway->delete(array('newsId' => $id));
    }
}