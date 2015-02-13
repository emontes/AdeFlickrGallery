<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Foto for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Foto\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Foto\Service\FotoService;
use Foto\Service\FlickrPhoto;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $id = '5964609109';
        }
        $grupoId = $this->params()->fromRoute('grupo');
        $page = $this->params()->fromRoute('page');
        
        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        $retval = array();
        if (isset($grupoId)) {
            $grupo = array(
              'link'  => '/grupos/' . $grupoId,
              'title' => $configFlickr['groups'][$grupoId]['title'],  
            );
            if (isset($page)) {
                $grupo['link'] .= '/pag/' . $page;
            }
            $retval['grupo'] = $grupo;
        }
        
        
        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        
        $fotoService = new FotoService();
        $flickr = new FlickrPhoto($configFlickr['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        
        $idarray = explode('+', $id);
        $photos = array();
        foreach ($idarray as $id) {
            $photos[] = array(
                'id'      => $id,
                'details' => $fotoService->getPhotoDetails($flickr, $id),
                'info'    => $fotoService->getPhotoInfo($flickr, $id),
                'exif'    => $fotoService->getPhotoExif($flickr, $id),
            );
        }
        
        $retval['photos'] = $photos;
        
        return $retval;
    }

}
