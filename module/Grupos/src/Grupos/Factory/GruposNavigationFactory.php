<?php
namespace Grupos\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Grupos\Navigation\GruposNavigation;

class GruposNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation = new GruposNavigation();
        return $navigation->createService($serviceLocator);
    }
}