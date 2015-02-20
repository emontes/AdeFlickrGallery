<?php
namespace AdeFlickrGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AdeFlickrGallery\Service\AlbumService;
use AdeFlickrGallery\Service\FlickrAlbum;

class AlbumController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        
        $albumId = $this->params()->fromRoute('id');
        
        $this->layout()->setVariable('bodyClass',
            'page-template-templatesportfolio-template ef-fullwidth-page ef-has-widgets');
        
        $flickr = new FlickrAlbum($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $albumService = new AlbumService();
        
        $albumInfo = $albumService->getAlbumInfo($flickr, $albumId);
        
        $pageTitle = 'Album: ' . $albumInfo['title'];
        
        return array(
            'albumInfo' => $albumInfo,
            'pageTitle' => $pageTitle,
        );
    }
}