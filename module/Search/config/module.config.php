<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Search\Controller\Search' => 'Search\Controller\SearchController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'search' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/search[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Search\Controller\Search',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'search' => __DIR__ .'/../view',
        ),
    ),
);