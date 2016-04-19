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
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/books[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Collection',
                        'action'     => 'collection',
                    ),
                ),
            ),
            'test' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/test',
                    'defaults' => array(
                        'controller' => 'Collection',
                        'action'     => 'collection',
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
