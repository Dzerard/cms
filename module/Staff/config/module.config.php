<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Staff\Controller\Staff' => 'Staff\Controller\StaffController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'staff' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/staff[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Staff\Controller\Staff',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'staff' => __DIR__ .'/../view',
        ),
    ),
);