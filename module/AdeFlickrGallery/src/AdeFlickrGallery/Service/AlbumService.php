<?php
namespace AdeFlickrGallery\Service;

use ZendService\Flickr\Exception\ExceptionInterface as FlickrException;
use Zend\Cache\StorageFactory;

class AlbumService
{
    
    public function getAlbumInfo($flickr, $albumId, $cacheDir = 'data/cache')
    {
        try {
            $albumInfo = $flickr->getAlbumInfo($albumId);
        } catch (FlickrException $e) {
            echo $e->getMessage();
        }
        return $albumInfo;
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