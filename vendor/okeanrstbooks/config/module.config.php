<?php
namespace OkeanrstBooks;

use OkeanrstBooks\Service\CollectionService;
use OkeanrstBooks\Service\CollectionMapper;
use OkeanrstBooks\Service\ImageService;

return array(
    'frontend' => [
        'bookfoto' => [
            'size' => ['width' => 80, 'height' => 80],
        ]
    ],
    'controllers' => array(
        'factories' => array(
			'Collection' => 'OkeanrstBooks\Factory\Controller\CollectionControllerFactory'            
		),
    ),
    'service_manager' => [
        'factories' => [
            CollectionService::class => Factory\Service\CollectionServiceFactory::class,
            CollectionMapper::class => Factory\Service\CollectionMapperFactory::class,
            ImageService::class => Factory\Service\ImageServiceFactory::class,
        ],
        'invokables' => [
            'doctrine_extensions.uploadable'    => 'Gedmo\Uploadable\UploadableListener'
        ],    
    ],
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
    ),
     
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
                            'route' => '/collection[/][:page][/:itemCount]',
                            'constraints' => array(
                                'page'     => '[0-9]+',
                                'itemCount' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'collection',
                                'page' => 1,
                            ),
                        ),
                    ),
                    'book' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/book/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',                                
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'book',                                
                            ),
                        ),
                    ),
                    'editbook' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/editbook[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',                                
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'editbook',                                
                            ),
                        ),
                    ),
                    'deletebook' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/deletebook/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',                                
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'deletebook',                                
                            ),
                        ),
                    ),
                    'getbooksbyauthor' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/booksbyauthor/:id[/:page][/:itemCount]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                                'page'     => '[0-9]+',
                                'itemCount' => '[0-9]+',
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'getbooksbyauthor',                                
                            ),
                        ),
                    ),
                    'getbooksbyrubric' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/booksbyrubric/:id[/:page][/:itemCount]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                                'page'     => '[0-9]+',
                                'itemCount' => '[0-9]+',
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'getbooksbyrubric',                                
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
                    'authors' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/authors[/:page][/:itemCount]',
                            'constraints' => array(                                
                                'page'     => '[0-9]+',
                                'itemCount' => '[0-9]+',
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'authors',                                
                            ),
                        ),
                    ),
                    'editauthor' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/editauthor[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',                                
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'editauthor',                                
                            ),
                        ),
                    ),
                    'deleteauthor' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/deleteauthor/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',                                
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'deleteauthor',                                
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
                    'rubrics' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/rubrics[/:page][/:itemCount]',
                            'constraints' => array(                                
                                'page'     => '[0-9]+',
                                'itemCount' => '[0-9]+',
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'rubrics',                                
                            ),
                        ),
                    ),
                    'editrubric' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/editrubric[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',                                
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'editrubric',                                
                            ),
                        ),
                    ),
                    'deleterubric' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/deleterubric/:id',
                            'constraints' => array(
                                'id'     => '[0-9]+',                                
                            ),                            
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'deleterubric',                                
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
                    'ajaxeditbook' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/ajaxeditbook',                                                        
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxeditbook',                                
                            ),
                        ),
                    ),
                    'ajaxdeletebook' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/ajaxdeletebook',                                                        
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxdeletebook',                                
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
                    'ajaxeditauthor' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/ajaxeditauthor',                                                        
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxeditauthor',                                
                            ),
                        ),
                    ),
                    'ajaxdeleteauthor' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/ajaxdeleteauthor',                                                        
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxdeleteauthor',                                
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
                    'ajaxeditrubric' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/ajaxeditrubric',                                                        
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxeditrubric',                                
                            ),
                        ),
                    ),
                    'ajaxdeleterubric' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/ajaxdeleterubric',                                                        
                            'defaults' => array(
                                'controller' => 'Collection',
                                'action'     => 'ajaxdeleterubric',                                
                            ),
                        ),
                    ),
                ),
            ),
            
        ),
    ),    
 );
