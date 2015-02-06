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
        if (count($idarray > 0)) {
          $id = $idarray[0];  
          $photoDetails2 = $photoService->getPhotoDetails($flickr, $idarray[1]);
//                   $debug = new Debug();
//                   $debug->dump($photoDetails2);
//                   die();
          $photoExif2 = $photoService->getPhotoExif($flickr, $idarray[1]);
          $photoInfo2 = $photoService->getPhotoInfo($flickr, $idarray[1]);
          $photoDetails3 = $photoService->getPhotoDetails($flickr, $idarray[2]); //$flickr->getImageDetails($id);
          $photoExif3 = $photoService->getPhotoExif($flickr, $idarray[2]);
          $photoInfo3 = $photoService->getPhotoInfo($flickr, $idarray[2]);
        }  
        
        $photoDetails = $photoService->getPhotoDetails($flickr, $id); //$flickr->getImageDetails($id);
        $photoExif = $photoService->getPhotoExif($flickr, $id);
        $photoInfo = $photoService->getPhotoInfo($flickr, $id);
        
        if (count($idarray > 0)) {
        $retArray = array(
            'photoInfo'    => $photoInfo,
            'photoDetails' => $photoDetails,
            'photoExif'    => $photoExif,
            'photoInfo2'    => $photoInfo2,
            'photoDetails2' => $photoDetails2,
            'photoExif2'    => $photoExif2,
            'photoInfo3'    => $photoInfo3,
            'photoDetails3' => $photoDetails3,
            'photoExif3'    => $photoExif3,
            'photo_id3'     => $id);
        } else {
            $retArray = array(
                'photoInfo'    => $photoInfo,
                'photoDetails' => $photoDetails,
                'photoExif'    => $photoExif,
                'photo_id'     => $id);
        }
       
        
        
        
       
        
        
        
        return $retArray;
       
    }
}