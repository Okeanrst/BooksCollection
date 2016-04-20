<?php
namespace OkeanrstBooks;

use OkeanrstBooks\Service\CollectionService;
use OkeanrstBooks\Service\CollectionMapper;

return array(
    'controllers' => array(
        'factories' => array(
			'Collection' => 'OkeanrstBooks\Factory\Controller\CollectionControllerFactory'            
		),
    ),
    'service_manager' => [
        'factories' => [
            CollectionService::class => Factory\Service\CollectionServiceFactory::class,
            CollectionMapper::class => Factory\Service\CollectionMapperFactory::class,
        ],
    ],
     // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'books' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/books',                    
                    'defaults' => array(
                        'controller' => 'Collection',
                        'action'     => 'collection',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'collection' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/collection[/][:page]',
                            'constraints' => array(
                                'page'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'collection',
                                'page' => 1,
                            ),
                        ),
                    ),
                    'newbook' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/newbook',                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'newbook',                                
                            ),
                        ),
                    ),
                    'newauthor' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/newauthor',                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'newauthor',                                
                            ),
                        ),
                    ),
                    'newrubric' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/newrubric',                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'newrubric',                                
                            ),
                        ),
                    ),
                    'ajaxnewbook' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/ajaxnewbook',                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxnewbook',                                
                            ),
                        ),
                    ),
                    'ajaxnewauthor' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/ajaxnewauthor',                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxnewauthor',                                
                            ),
                        ),
                    ),
                    'ajaxnewrubric' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/ajaxnewrubric',                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxnewrubric',                                
                            ),
                        ),
                    ),
                    
                    
                ),
            ),
            
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/books'           => __DIR__ . '/../view/layout/books.phtml',            
        ),
        'template_path_stack' => array(
            'books' => __DIR__ . '/../view',
        ),
        
    ),    
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),        
    )
 );
