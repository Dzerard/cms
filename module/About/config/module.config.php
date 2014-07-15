<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'About\Controller\About' => 'About\Controller\AboutController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'about' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/about[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'About\Controller\About',
                        'action'     => 'index',
                    ),
                ),
            ),            
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'about' => __DIR__ .'/../view',
        ),
    ),
   
   
    
);