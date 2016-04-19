<?php

namespace OkeanrstBooks\Factory\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use OkeanrstBooks\Service\CollectionService;
use OkeanrstBooks\Controller\CollectionController;

class CollectionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $parentLocator = $container->getServiceLocator();
        $collectionService = $parentLocator->get(CollectionService::class);
        $entityManager = $parentLocator->get('doctrine.entitymanager.orm_default');
        return new CollectionController($collectionService, $entityManager);
    }
    
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, CollectionController::class);
    }
}