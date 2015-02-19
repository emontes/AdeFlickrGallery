<?php
namespace AdeFlickrGallery\Service;

use ZendService\Flickr\Exception\ExceptionInterface as FlickrException;
use Zend\Cache\StorageFactory;

class ColeccionesService
{
    public function getCollectionInfo($flickr, $userId, $collectionId, $cacheDir = 'data/cache')
    {
        try {
            $collectionInfo = $flickr->getCollectionInfo($userId, $collectionId);
        } catch (FlickrException $e) {
            echo $e->getMessage();
        }
        
        return $collectionInfo;
    }
    
    public function getCollectionTreeMenu($flickr, $userId, $collectionId, $cacheDir = 'data/cache')
    {
        $cache = $this->getCache($cacheDir);
        $cacheId = 'collectionTreeMenu' . $collectionId;
        $collectionTree = $cache->getItem($cacheId, $success);
        if (!$success) {
            try {
                $collectionTree = $flickr->getColletionTreeMenu($userId, $collectionId);
                $cache->setItem($cacheId, $collectionTree);
            } catch (FlickrException $e) {
                echo $e->getMessage();
            }
        }
        return $collectionTree;
    }
    
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
    
    
}