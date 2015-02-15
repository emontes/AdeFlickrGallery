<?php
namespace AdeFlickrGallery\Navigation;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class GruposNavigation extends DefaultNavigationFactory
{
   protected function getPages(ServiceLocatorInterface $serviceLocator)
   {
           $application = $serviceLocator->get('Application');
           $routeMatch  = $application->getMvcEvent()->getRouteMatch();
           $router      = $application->getMvcEvent()->getRouter();
           $selGroup = '';
           if ($routeMatch) {
               $selGroup    = $routeMatch->getParam('id');
           } 
           
           
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
                   'id'     => $idGrupo,
                   'label'  => $grupo['title'],
                   'uri'    => '/grupos/' . $grupo['uri'],
                   'active' => $active,
               );
           }
           
           $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
           
           $this->pages = $this->injectComponents($pages, $routeMatch, $router);
           
       $this->pages = $this->injectComponents($pages, $routeMatch, $router);
       return $this->pages;
       
       
   }
   
}