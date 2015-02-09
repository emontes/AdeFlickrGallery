<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Grupos for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Grupos\Controller;

use ZendService\Flickr\Flickr;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
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
       
        
        $flickr = new Flickr($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        
        $gruposService = $this->getServiceLocator()->get('grupos-service');
        
        $groupInfo = $gruposService->getGroupInfo($groupId);
        $fotos = $gruposService->getFotos($flickr,$groupId, $page);
        $groupTags = $gruposService->getGroupTags($flickr, $fotos);
        $tags = $groupTags[0];
        $fotosConTags = $groupTags[1];
        
        
        $categorias = $gruposService->getGroupCategories($tags);
        $fotosConCategoria = $gruposService->getPhotosCategories($fotosConTags, $categorias); 
        //\Zend\Debug\Debug::dump($fotosConCategoria,'fotos',true);die();
        $fotos = $gruposService->makeFotosTrio($fotos);
       
        
        
        $pageTitle = 'Grupo: ' . $configFlickr['groups'][$id]['title'];
        if ($page > 1) {
            $pageTitle .= ' - Pag. ' . $page;
        }
        return array(
            'fotos' => $fotos,
            'id'         => $id,
            'page'       => $page,
            'pageTitle'  => $pageTitle,
            'groupInfo'  => $groupInfo,
            'tags'       => $tags,
            'categorias' => $categorias,
            'fotosConCategoria' => $fotosConCategoria,
        );
    }
    
   
    
    
    
   

}


