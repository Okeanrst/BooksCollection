<?php

namespace OkeanrstBooks\Factory\Service;

use Interop\Container\ContainerInterface;
use OkeanrstBooks\Service\CollectionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface; 

class CollectionServiceFactory  implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $em = $container->get('doctrine.entitymanager.orm_default');
        return new CollectionService($em);
    }
    
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, CollectionService::class);
    }
}