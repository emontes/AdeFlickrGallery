<?php
namespace Home\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendService\Flickr\Exception\ExceptionInterface as FlickrException;

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
            try {
                $photoDetails = $flickr->getImageDetails($id);
            } catch (FlickrException $e) {
                $photoDetails = array('Error'=> $e->getMessage());
            }
            
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
              try {
                  $photoExif = $flickr->getPhotoExif($id);
              } catch (FlickrException $e) {
                  $photoExif = array('error' => $e->getMessage());
              }
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
            try {
                $photoInfo = $flickr->getImageInfoById($id);
            } catch (FlickrException $e) {
                $photoInfo = array('Error' => $e->getMessage());
            }
            
            $cache->setItem($cacheId, $photoInfo);
        }
        return $photoInfo;
    }
}