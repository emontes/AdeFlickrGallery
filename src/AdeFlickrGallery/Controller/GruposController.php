<?php
namespace AdeFlickrGallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use AdeFlickrGallery\Service\FlickrGroups;
use AdeFlickrGallery\Service\GruposService;

class GruposController extends AbstractActionController
{
    public function indexAction()
    {

        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];

        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $id = 'turistapuebla';
        }
        $groupId = $configFlickr['groups'][$id]['id'];

        $page = (int) $this->params()->fromRoute('page');
        if (!$page) {
            $page = 1;
        }

        $this->layout()->setVariable('bodyClass',
            'page-template-templatesportfolio-template ef-fullwidth-page ef-has-widgets');
         

        $flickr = new FlickrGroups($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));

        $gruposService = new GruposService();

        $groupInfo = $gruposService->getGroupInfo($flickr, $groupId);
        $groupTopics = $gruposService->getGroupTopics($flickr, $groupId);
        $fotos = $gruposService->getFotos($flickr,$groupId, $page);
        $groupTags = $gruposService->getGroupTags($flickr, $fotos);
        $tags = $groupTags[0];
        $fotosConTags = $groupTags[1];


        $categorias = $gruposService->getGroupCategories($tags);
        $fotosConCategoria = $gruposService->getPhotosCategories($fotosConTags, $categorias);
        $fotos = $gruposService->makeFotosTrio($fotos);
         


        $pageTitle = 'Grupo: ' . $configFlickr['groups'][$id]['title'];
        if ($page > 1) {
            $pageTitle .= ' - Pag. ' . $page;
        }
        return array(
            'fotos' => $fotos,
            'id'                => $id,
            'page'              => $page,
            'pageTitle'         => $pageTitle,
            'groupInfo'         => $groupInfo,
            'groupTopics'       => $groupTopics,
            'tags'              => $tags,
            'categorias'        => $categorias,
            'fotosConCategoria' => $fotosConCategoria,
            'allowedTags'       => $configFlickr['allowedTags'],
        );
    }

}