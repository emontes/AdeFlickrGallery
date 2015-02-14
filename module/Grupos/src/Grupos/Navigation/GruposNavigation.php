<?php
namespace Grupos\Navigation;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class GruposNavigation extends DefaultNavigationFactory
{
   protected function getPages(ServiceLocatorInterface $serviceLocator)
   {
       if (null === $this->pages) {
           
       }
       
       $application = $serviceLocator->get('Application');
       $routeMatch  = $application->getMvcEvent()->getRouteMatch();
       $router      = $application->getMvcEvent()->getRouter();
       $selGroup    = $routeMatch->getParam('id');
       
       $config = $serviceLocator->get('config');
       $grupos = $config['flickr']['groups'];
       
       $configuration['navigation'][$this->getName()] = array();
       foreach ($grupos as $idGrupo => $grupo) {
           if ($selGroup == $idGrupo) {
               $active = true;
           } else {
               $active = false;
           }
           $configuration['navigation'][$this->getName()][$idGrupo] = array(
               'label' => $grupo['title'],
               'uri'    => '/grupos/' . $grupo['uri'],
               'active' => $active,
           );
       }
       
       $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
       
       $this->pages = $this->injectComponents($pages, $routeMatch, $router);
       return $this->pages;
       
   }
   
}