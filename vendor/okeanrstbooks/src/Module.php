<?php

namespace OkeanrstBooks;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Zend\EventManager\EventInterface;

class Module implements InitProviderInterface, AutoloaderProviderInterface, ConfigProviderInterface, BootstrapListenerInterface
{
    public function init(ModuleManagerInterface $manager)
    {
        AnnotationRegistry::registerAutoloadNamespace('Gedmo\Mapping\Annotation', 'vendor/gedmo/doctrine-extensions/lib');
    }
    
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getParam('application');
        $app->getEventManager()->attach('render', array($this, 'setLayout'));
    }
    
    public function setLayout($e)
    {
        $viewModel = $e->getViewModel();
        $viewModel->setTemplate('layout/books');
    }
    
    public function getConfig()
    {
        return include dirname(__DIR__) . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => dirname(__DIR__) . '/src',
                ],
            ],
        ];
    }
}