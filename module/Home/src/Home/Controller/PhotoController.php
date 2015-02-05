<?php

namespace Home\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendService\Flickr\Flickr;
use Zend\Debug\Debug;

class PhotoController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->layout()->configFlickr;
        
        
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            $id = '5964609109';
        }
        $photoService = $this->getServiceLocator()->get('photo-service');
        
        $flickr = new Flickr($config['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $photoDetails = $photoService->getPhotoDetails($flickr, $id); //$flickr->getImageDetails($id);
        $photoExif = $photoService->getPhotoExif($flickr, $id);
        $photoInfo = $photoService->getPhotoInfo($flickr, $id);
        
//         $debug = new Debug();
//                                             $debug->dump($photoExif);
//                                             die();
        return array(
            'photoInfo'    => $photoInfo,
            'photoDetails' => $photoDetails,
            'photoExif'    => $photoExif,
        );
    }
}