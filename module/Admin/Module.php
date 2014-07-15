<?php

namespace Admin;

use Admin\Model\Contact;
use Admin\Model\ContactTable;
use Admin\Model\Notification;
use Admin\Model\NotificationTable;
use Admin\Model\News;
use Admin\Model\NewsTable;
use Admin\Model\Offer;
use Admin\Model\OfferTable;
use Admin\Model\User;
use Admin\Model\UserTable;
use Admin\Model\Info;
use Admin\Model\InfoTable;
use Admin\Model\Visualization;
use Admin\Model\VisualizationTable;
use Admin\Model\Design;
use Admin\Model\DesignTable;
use Admin\Model\Message;
use Admin\Model\MessageTable;
use Admin\Model\File;
use Admin\Model\FileTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module {
        
    public function getAutoloaderConfig()
    {
        return array(
        'Zend\Loader\ClassMapAutoloader' => array(
            __DIR__ . '/autoload_classmap.php',
        ),
        'Zend\Loader\StandardAutoloader' => array(
            'namespaces' => array(
                __NAMESPACE__ => __DIR__ .'/src/' . __NAMESPACE__,
            ),
        ),    
                
        );
        
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
        
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                
                'Admin\Model\MyAuthStorage' => function($sm){
                    return new \Admin\Model\MyAuthStorage('zf_tutorial');  
                },
                        
                'AuthService' => function($sm) {
                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 
                                              'users','user_name','pass_word', 'MD5(?)');
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('Admin\Model\MyAuthStorage'));
                    
                    return $authService;
                    
                },
                      
                //prawdopodobnie do usuniecia
                'Admin\Model\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },

                'Admin\Model\ContactTable' =>  function($sm) {
                    $tableGateway = $sm->get('ContactTableGateway');
                    $table = new ContactTable($tableGateway);
                    return $table;
                },                   
                        
                //newsletter
                'Admin\Model\NotificationTable' =>  function($sm) {
                    $tableGateway = $sm->get('NotificationTableGateway');
                    $table = new NotificationTable($tableGateway);
                    return $table;
                },               
                        
                'Admin\Model\NewsTable' =>  function($sm) {
                    $tableGateway = $sm->get('NewsTableGateway');
                    $table = new NewsTable($tableGateway);
                    return $table;
                },
                        
                'Admin\Model\OfferTable' =>  function($sm) {
                    $tableGateway = $sm->get('OfferTableGateway');
                    $table = new OfferTable($tableGateway);
                    return $table;
                },
                        
                'Admin\Model\InfoTable' =>  function($sm) {
                    $tableGateway = $sm->get('InfoTableGateway');
                    $table = new InfoTable($tableGateway);
                    return $table;
                },
                        
                'Admin\Model\VisualizationTable' =>  function($sm) {
                    $tableGateway = $sm->get('VisualizationTableGateway');
                    $table = new VisualizationTable($tableGateway);
                    return $table;
                },   
                        
                'Admin\Model\DesignTable' =>  function($sm) {
                    $tableGateway = $sm->get('DesignTableGateway');
                    $table = new DesignTable($tableGateway);
                    return $table;
                },  
                        
                'Admin\Model\MessageTable' =>  function($sm) {
                    $tableGateway = $sm->get('MessageTableGateway');
                    $table = new MessageTable($tableGateway);
                    return $table;
                },  
                        
                'Admin\Model\FileTable' =>  function($sm) {
                    $tableGateway = $sm->get('FileTableGateway');
                    $table = new FileTable($tableGateway);
                    return $table;
                }, 
                 
                'FileTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new File());
                    return new TableGateway('file', $dbAdapter, null, $resultSetPrototype);
                },  
                        
                'MessageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Message());
                    return new TableGateway('message', $dbAdapter, null, $resultSetPrototype);
                },      
                        
                'DesignTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Design());
                    return new TableGateway('design', $dbAdapter, null, $resultSetPrototype);
                },
                       
                'VisualizationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Visualization());
                    return new TableGateway('visualization', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'ContactTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Contact());
                    return new TableGateway('contact', $dbAdapter, null, $resultSetPrototype);
                },       
             
                'NotificationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Notification());
                    return new TableGateway('notification', $dbAdapter, null, $resultSetPrototype);
                },
                                                
                'NewsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new News());
                    return new TableGateway('news', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'OfferTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Offer());
                    return new TableGateway('offer', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                        
                'InfoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Info());
                    return new TableGateway('info', $dbAdapter, null, $resultSetPrototype);
                },
                        
            ),
        );
    }
 
    
}
