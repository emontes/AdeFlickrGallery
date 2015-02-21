<?php
namespace AdeFlickrGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AdeFlickrGallery\Service\ColeccionesService;
use AdeFlickrGallery\Service\FlickrCollections;

class ColeccionesController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        $flickr = new FlickrCollections($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $collectionsService = new ColeccionesService();
        
        $id = $this->params()->fromRoute('id');
        $userId = $configFlickr['user_id'];
        //$collectionId = $this->config['flickr']['collections']['puebla'];
        $this->layout()->setVariable('bodyClass',
            'ef-fullwidth-page ef-gallery ef-has-widgets');
        
        $collectionInfo = $collectionsService->getCollectionInfo($flickr, $userId, $id);
        return array(
            'collectionInfo' => $collectionInfo,
        );
    }
    
}