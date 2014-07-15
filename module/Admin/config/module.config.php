<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
            'Admin\Controller\Ajax'  => 'Admin\Controller\AjaxController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Admin',
                        'action'     => 'index',
                    ),
                ),
            ), 
            'ajax' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/ajax[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',                        
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Ajax',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'admin' => __DIR__ .'/../view',
        ),  
        'template_map' => array(
            'partial/bread'         => __DIR__ . '/../view/partial/bread-crumbs.phtml',  
        ),
         // let the view manager know which strategies to use
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    
//    'view_manager' => array(
//
//        'not_found_template'       => 'error/404',
//        'exception_template'       => 'error/index',
//        
//        'template_map' => array(
//              'myError'               => __DIR__ . '/../view/error/404.phtml',
//          //  'error/404'               => __DIR__ . '/../view/error/404.phtml',
//          //  'error/index'             => __DIR__ . '/../view/error/index.phtml',
//         
//        ),
//      
//    ),
//  
    
//    'navigation' => array(
//        
//        'second' => array(
//          
//          array(
//            'label' => 'Strona główna',
//            'route' => 'home'
//          ),
//      ),
//    ),
//    
//    'service_manager' => array(
//        'factories' => array(
//            'second_navigation' => 'Admin\Navigation\Service\SecondaryNavigationFactory'
//        ),
//),
   
);