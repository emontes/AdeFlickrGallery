<?php
namespace Home\Service;

use ZendService\Flickr\Exception\ExceptionInterface as FlickrException;
use Zend\Cache\StorageFactory;

class PhotoService 
{
    
    private function getCache($cacheDir='data/cache', $ttl=604800) {
        return StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem',
                'options' => array(
                    'cache_dir' => $cacheDir,
                    'ttl' => $ttl,
                ),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
                'serializer',
            ),
        ));
    }
    
    public function getPhotoDetails($flickr, $id, $cacheDir = 'data/cache')
    {
        //$cache = $this->getServiceLocator()->get('filesystem');
        $cache = $this->getCache($cacheDir);
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
    
    public function getPhotoExif($flickr, $id, $cacheDir = 'data/cache')
    {
        $cache = $this->getCache($cacheDir);
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
    
    public function getPhotoInfo($flickr, $id, $cacheDir = 'data/cache')
    {
        $cache = $this->getCache($cacheDir);
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