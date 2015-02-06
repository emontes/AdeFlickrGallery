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
        $config = $this->layout()->configFlickr;
        
        
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $id = 'turistapuebla';
        }
        $groupId = $config['groups'][$id]['id'];
        
        $page = (int) $this->params()->fromRoute('page');
        if (!$page) {
            $page = 1;
        }
        
        
        $flickr = new Flickr($config['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        
        $gruposService = $this->getServiceLocator()->get('grupos-service');
        $groupInfo = $gruposService->getGroupInfo($flickr, $groupId);
        $fotos = $gruposService->getFotosTrio($flickr,$id, $groupId, $page);
        
        
        $pageTitle = 'Grupo: ' . $config['groups'][$id]['title'];
        if ($page > 1) {
            $pageTitle .= ' - Pag. ' . $page;
        }
        return array(
            'fotos' => $fotos,
            'id'   => $id,
            'page' => $page,
            'pageTitle' => $pageTitle,
            'groupInfo' => $groupInfo,
        );
    }
    
   
    
    
    
   

}


