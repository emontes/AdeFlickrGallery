<?php
namespace AdeFlickrGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AdeFlickrGallery\Service\AlbumService;
use AdeFlickrGallery\Service\FlickrAlbum;
use AdeFlickrGallery\Service\GruposService;

class AlbumController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        
        $albumId = $this->params()->fromRoute('id');
        
        $page = (int) $this->params()->fromRoute('page');
        if (!$page) {
            $page = 1;
        }
        
        $this->layout()->setVariable('bodyClass',
            'page-template-templatesportfolio-template ef-fullwidth-page ef-has-widgets');
        
        $flickr = new FlickrAlbum($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $albumService = new AlbumService();
        
        $albumInfo = $albumService->getAlbumInfo($flickr, $albumId);
        $pageTitle = 'Album: ' . $albumInfo['title'];
        
        $fotos = $albumService->getFotos($flickr, $albumId, $page);
        
        $gruposService = new GruposService();
        
        $albumTags = $gruposService->getGroupTags($flickr, $fotos);
        $tags = $albumTags[0];
        $fotosConTags = $albumTags[1];
        
        $categorias = $gruposService->getGroupCategories($tags);
        $fotosConCAtegoria = $gruposService->getPhotosCategories($fotosConTags, $categorias);
        $fotos = $gruposService->makeFotosTrio($fotos);
        
        return array(
            'id'                => $albumId,
            'page'              => $page,
            'albumInfo'         => $albumInfo,
            'pageTitle'         => $pageTitle,
            'fotos'             => $fotos,
            'tags'              => $tags,
            'categorias'        => $categorias,
            'fotosConCategoria' => $fotosConCAtegoria,
        );
    }
}