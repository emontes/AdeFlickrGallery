<?php
namespace AdeFlickrGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AdeFlickrGallery\Service\TagService;
use AdeFlickrGallery\Service\FlickrPhoto;
use AdeFlickrGallery\Service\GruposService;

class TagController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        
        $tag = $this->params()->fromRoute('tag');
        
        $notAllowedTags = $configFlickr['notAllowedTags'];
        if (in_array($tag, $notAllowedTags)) {
            $this->flashmessenger()->addErrorMessage('No estamos autorizados para desplegar el tag' . $tag);
            $this->redirect()->toRoute('home');
        }
        
        $page = (int) $this->params()->fromRoute('page');
        if (!$page) {
            $page = 1;
        }
        $this->layout()->setVariable('bodyClass',
            'page-template-templatesportfolio-template ef-fullwidth-page ef-has-widgets');
        
        
        $flickr = new FlickrPhoto($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $tagService = new TagService();
        
        $pageTitle = 'Tag: ' . $tag;
        if ($page > 1) {
            $pageTitle .= ' Pag. ' . $page;
        }
        
        $fotosResult = $tagService->getFotos($flickr, $tag, $page);
        
        $fotos = $fotosResult['fotos'];
        $totalFotos = $fotosResult['count_photos'];
        
        $gruposService = new GruposService();
        
        $tagTags = $gruposService->getGroupTags($flickr, $fotos);
        $tags = $tagTags[0];
        $fotosConTags = $tagTags[1];
        
        $categorias = $gruposService->getGroupCategories($tags);
        $fotosConCAtegoria = $gruposService->getPhotosCategories($fotosConTags, $categorias);
        $fotos = $gruposService->makeFotosTrio($fotos);
        
        
        
        return array(
            'id'                => $tag,
            'page'              => $page,
            'totalFotos'        => $totalFotos,
            'pageTitle'         => $pageTitle,
            'fotos'             => $fotos,
            'tags'              => $tags,
            'categorias'        => $categorias,
            'fotosConCategoria' => $fotosConCAtegoria,
        );
    }
}
