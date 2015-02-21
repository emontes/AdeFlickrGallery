<?php
namespace AdeFlickrGallery\Service;

use ZendService\Flickr\Exception\ExceptionInterface as FlickrException;
use Zend\Cache\StorageFactory;

class AlbumService
{
    public function getFotos($flickr, $albumId, $page, $perPage = 20, $cacheDir='data/cache')
    {
        try {
            $results = $flickr->getAlbumPhotos($albumId, array(
                'page' => $page,
                'per_page' => $perPage
            ));
            $fotos = array();
            foreach ($results as $result) {
        
        
                    if (isset($result['title'])){
                        $title = $result['title'];
                    } else {
                        $title = '';
                    }
                    if (isset($result['details']['large'])) {
                        $largeImg = $result['details']['large']->uri;
                    } else {
                        if (isset($result['details']['medium'])) {
                            $largeImg = $result['details']['medium']->uri;
                        } else {
                            $largeImg = $result['details']['original']->uri;
                        }
                        
                    }
                    if (isset($result['details']['medium']->uri)) {
                        $mediumImg = $result['details']['medium']->uri;
                    } else {
                        $mediumImg = $result['details']['original']->uri;
                    }
                    $fotos[] = array(
                        'id'       => $result['id'],
                        'uri'      => $mediumImg,
                        'title'    => $title,
                        'largeImg' => $largeImg,
                    );
        
                     
            }
        
            //$cache->setItem($cacheId, $fotos);
        } catch (FlickrException $e) {
            echo $e->getMessage();
        }
        return $fotos;
    }
    
    public function getAlbumInfo($flickr, $albumId, $cacheDir = 'data/cache')
    {
        $cache = $this->getCache($cacheDir);
        $cacheId = 'albumInfo' . $albumId;
        $albumInfo = $cache->getItem($cacheId, $success);
        if (!$success) {
            try {
                $albumInfo = $flickr->getAlbumInfo($albumId);
                $cache->setItem($cacheId, $albumInfo);
            } catch (FlickrException $e) {
                echo $e->getMessage();
            }
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