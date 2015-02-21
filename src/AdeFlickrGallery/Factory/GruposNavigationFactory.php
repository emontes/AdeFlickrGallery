<?php
namespace AdeFlickrGallery\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use AdeFlickrGallery\Navigation\GruposNavigation;

class GruposNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
       $navigation = new GruposNavigation();
       return $navigation->createService($serviceLocator);
    }
}