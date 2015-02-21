<?php
namespace AdeFlickrGallery;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use AdeFlickrGallery\Service\ColeccionesService;
use AdeFlickrGallery\Service\FlickrCollections;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 100);
    }
    
    public function onDispatch(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $config = $sm->get('config');
        $configFlickr = $config['flickr'];
        $layoutView = $e->getViewModel();
        $layoutView->setVariable('configFlickr', $configFlickr);
        $layoutView->setVariable('externalSites', $config['external_sites']);
        $layoutView->setVariable('siteName', $config['siteName']);
        $flickr = new FlickrCollections($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $collectionsService = new ColeccionesService();
        $collections = $configFlickr['collections'];
        $cuantas = count($collections);
        if ($cuantas > 1) {
            $menu = array(
                'label' => 'Colecciones',
                'uri'   => '#',
            );
        }
        
        foreach ($collections as $collection) {
            if ($cuantas > 1) {
                $menu['pages'][] = $collectionsService->getCollectionTreeMenu(
                    $flickr, $configFlickr['user_id'], $collection);
            } else {
                $menu = $collectionsService->getCollectionTreeMenu(
                    $flickr, $configFlickr['user_id'], $collection);
            }
            
        }
        
        $layoutView->setVariable('menuCollections', $menu);
    }
}
