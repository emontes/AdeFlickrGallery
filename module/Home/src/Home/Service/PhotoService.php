<?php
namespace Home\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Debug\Debug;

class PhotoService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator() {
        return $this->serviceLocator;
    }
    
    public function getPhotoDetails($flickr, $id)
    {
        $cache = $this->getServiceLocator()->get('filesystem');
        $cacheId = 'photoDetails-'. $id;
        $photoDetails = $cache->getItem($cacheId, $success);
        if (!$success) {
            $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
            $photoDetails = $flickr->getImageDetails($id);
            $cache->setItem($cacheId, $photoDetails);
        }
        return $photoDetails;
    }
    
    public function getPhotoExif($flickr, $id)
    {
        $cache = $this->getServiceLocator()->get('filesystem');
        $cacheId = 'photoExif-'. $id;
        $photoExif = $cache->getItem($cacheId, $success);
        if (!$success) {
            $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
            $photoExif = $flickr->getPhotoExif($id);
            $cache->setItem($cacheId, $photoExif);
        }
        return $photoExif;
    }
    
    public function getPhotoInfo($flickr, $id)
    {
        $cache = $this->getServiceLocator()->get('filesystem');
        $cacheId = 'photoInfo-'. $id;
        $photoInfo = $cache->getItem($cacheId, $success);
        if (!$success) {
            $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
            $photoInfo = $flickr->getImageInfoById($id);
            $cache->setItem($cacheId, $photoInfo);
        }
        return $photoInfo;
    }
}