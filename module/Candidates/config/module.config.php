<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Candidates\Controller\Candidates' => 'Candidates\Controller\CandidatesController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'candidates' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/candidates[/:action][/:category][/:id]',
                    'constraints' => array(
                        'action'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'       => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Candidates\Controller\Candidates',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    

    'view_manager' => array(
        'template_path_stack' => array(
            'candidates' => __DIR__ .'/../view',
        ),
        'template_map' => array(
            'layout/popular'         => __DIR__ . '/../view/partial/popular_offers_partial.phtml',  
            'layout/categories'      => __DIR__ . '/../view/partial/offer_category.phtml',  
        ),
    ),
);