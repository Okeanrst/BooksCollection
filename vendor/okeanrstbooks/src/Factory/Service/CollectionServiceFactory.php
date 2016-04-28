<?php

namespace OkeanrstBooks\Factory\Service;

use Interop\Container\ContainerInterface;
use OkeanrstBooks\Service\CollectionService;
use OkeanrstBooks\Service\CollectionMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OkeanrstBooks\Service\ImageService; 

class CollectionServiceFactory  implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $mapper = $container->get(CollectionMapper::class);
        $imageService = $container->get(ImageService::class);
        $config = $container->get('config')['frontend'];
        return new CollectionService($mapper, $imageService, $config);
    }
    
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, CollectionService::class);
    }
}
