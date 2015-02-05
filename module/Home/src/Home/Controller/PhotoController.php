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
            $id = 'turistapuebla';
        }
        
        $flickr = new Flickr($config['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $photoDetails = $flickr->getImageDetails($id);
        $photoInfo = $flickr->getImageInfoById($id);
//         $photoExif = $flickr->getImageInfoById($id,'flickr.photos.getExif');
        
//         $debug = new Debug();
//                                             $debug->dump($photoExif);
//                                             die();
        return array(
            'photoInfo'    => $photoInfo,
            'photoDetails' => $photoDetails,
        );
    }
}