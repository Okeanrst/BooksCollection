<?php

namespace OkeanrstBooks\Factory\Service;

use Interop\Container\ContainerInterface;
use OkeanrstBooks\Mapper\CollectionMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface; 

class CollectionMapperFactory  implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $em = $container->get('doctrine.entitymanager.orm_default');
        return new CollectionMapper($em);
    }
    
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, CollectionMapper::class);
    }
}