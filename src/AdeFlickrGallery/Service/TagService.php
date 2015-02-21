<?php 
namespace AdeFlickrGallery\Service;

use ZendService\Flickr\Exception\ExceptionInterface as FlickrException;
use Zend\Cache\StorageFactory;

class TagService
{
    public function getFotos($flickr, $tag, $page, $perPage = 20, $cacheDir='data/cache')
    {
        $cache = $this->getCache($cacheDir);
        $cacheId = 'fotosTag' . $tag . '-pag-' . $page;
        $fotos = $cache->getItem($cacheId, $success);
        if (!$success) {
            try {
                $results = $flickr->tagSearch($tag, array(
                    'page'     => $page,
                    'per_page' => $perPage,
                ));
                $totalResults = $results->totalResultsAvailable;
                $fotos = array();
                foreach ($results as $result) {
                    if (isset($result->Medium)) {
                        if (isset($result->title)){
                            $title = $result->title;
                        } else {
                            $title = '';
                        }
                        if (isset($result->Large)){
                            $largeImg = $result->Large->uri;
                        } else {
                            $largeImg = $result->Medium->uri;
                        }
                        $fotos[] = array(
                            'id'       => $result->id,
                            'uri'      => $result->Medium->uri,
                            'title'    => $title,
                            'largeImg' => $largeImg,
                        );
                    }
                }
                $fotos = array(
                    'fotos'        => $fotos,
                    'count_photos' => $totalResults
                );
                $cache->setItem($cacheId, $fotos);
            } catch (FlickrException $e) {
                echo $e->getMessage();
            }
        }
       
        
        return $fotos;
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