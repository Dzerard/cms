<?php

namespace News;

use News\Model\NewsTable;
use News\Model\News;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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
    
    
}
