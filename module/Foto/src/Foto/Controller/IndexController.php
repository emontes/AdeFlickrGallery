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
use ZendService\Flickr\Flickr;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $id = '5964609109';
        }
        
        $config = $this->getServiceLocator()->get('config');
        $configFlickr = $config['flickr'];
        
        $fotoService = new FotoService();
        $flickr = new Flickr($configFlickr['key']);
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
        
        
        
        return array(
            'photos' => $photos,
        );
    }

}
