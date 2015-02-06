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
        
        
        
        $idarray = explode('+', $id);
        $photos = array();
        foreach ($idarray as $id) {
            $photos[] = array(
                'id'      => $id,  
                'details' => $photoService->getPhotoDetails($flickr, $id),
                'info'    => $photoService->getPhotoInfo($flickr, $id),
                'exif'    => $photoService->getPhotoExif($flickr, $id),
            );
        }
        
        return array('photos' => $photos);
       
    }
}