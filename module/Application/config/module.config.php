<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'segment', //Zend\Mvc\Router\Http\Literal
                'options' => array(
                    'route'    => '/[:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                       
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        

        'factories' => array(
         //'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        // 'secondary_navigation'  => 'Admin\Navigation\Service\SecondaryNavigationFactory',
         'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory', // <-- add this
        
  
       
        ),
    ),
    'translator' => array(   
        'locale' => 'pl',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => 'public/language/',
                //  'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    
     'navigation' => array(      
                        
        'default' => array(
          
          array(
            'label' => 'Strona główna',
            'route' => 'home'
          ),
          array(
              'label' => 'O Projekcie',
              'route' => 'about',
              'pages' => array(
                  array(
                      'label'  => 'Kariera',
                      'route'  => 'about',
                      'action' => 'career'
                  ),               
              ),
          ),
//          array(
//              'label' => 'Dla Pracodawców',
//              'route' => 'client',
//              'pages' => array(
//                  array(
//                      'label'  => 'Praca tymczasowa',
//                      'route'  => 'client',
//                      'action' => 'work'
//                  ),  
//                  array(
//                      'label'  => 'Zamów usługę',
//                      'route'  => 'client',
//                      'action' => 'order'
//                  ),  
//                  
//              ),
//          ),
//          array(
//              'label' => 'Dla Kandydatów',
//              'route' => 'candidates',
//              'pages' => array(
//                  array(
//                      'label'  => 'Oferty pracy',
//                      'route'  => 'candidates',
//                      'action' => 'offer'
//                  ),
//                  array(
//                      'label'  => 'Poleć znajomego',
//                      'route'  => 'candidates',
//                      'action' => 'recommend',
//                  ), 
//                
//                  array(
//                      'label'  => 'Wybór właściwej firmy',
//                      'route'  => 'candidates',
//                      'action' => 'apply',
//                  ), 
//                  array(
//                      'label'  => 'Referencje',
//                      'route'  => 'candidates',
//                      'action' => 'reference',
//                  ), 
//                  
//                  
//              ),
//          ),
//          
//          array(
//              'label' => 'Praca za granicą',
//              'route' => 'care',
//              'pages' => array(
//                  array(
//                      'label'  => 'Opieka os. starszych',
//                      'route'  => 'care',
//                      'action' => 'index'
//                  ),   
//                  array(
//                      'label'  => 'Oferta',
//                      'route'  => 'care',
//                      'action' => 'offer'
//                  ),    
//                  array(
//                      'label'  => '3 kroki do wyjazdu',
//                      'route'  => 'care',
//                      'action' => 'question'
//                  ), 
//                  array(
//                      'label'  => 'FAQ',
//                      'route'  => 'care',
//                      'action' => 'faq'
//                  ), 
//                  array(
//                      'label'  => 'Przygotowania do wyjazdu',
//                      'route'  => 'care',
//                      'action' => 'departure'
//                  ), 
//                  array(
//                      'label'  => 'Personel medyczny',
//                      'route'  => 'care',
//                      'action' => 'personel'
//                  ), 
//                  
//              ),
//          ),
          array(
              'label' => 'Kontakt',
              'route' => 'contact',              
          ),
            
        ),
    ),
    
    
);
