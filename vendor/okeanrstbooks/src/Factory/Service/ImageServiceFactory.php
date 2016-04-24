<?php

namespace OkeanrstBooks\Factory\Service;

use OkeanrstBooks\Service\ImageService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImageServiceFactory  implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {        
        return new ImageService();
    }
    
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, ImageService::class);
    }
}