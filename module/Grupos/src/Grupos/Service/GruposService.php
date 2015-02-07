<?php
namespace Grupos\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendService\Flickr\Flickr;
use ZendService\Flickr\Exception\ExceptionInterface as FlickrException;
use Zend\Debug\Debug;

class GruposService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $flickr;
    
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator() {
        return $this->serviceLocator;
    }
    
    public function getConfig() {
        return $this->getServiceLocator()->get('Config');
    }
    
    public function getFlickr() {
        $config = $this->getConfig();
        $flickr = new Flickr($config['flickr']['key']);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        return $flickr;
        
    }
    
    public function getGroupInfo($groupId)
    {
        
        $cache = $this->getServiceLocator()->get('filesystem');
        $normalizedGroupId = str_replace('@', 'at', $groupId);
        $cacheId = 'grupoInfo-' . $normalizedGroupId;
        $groupInfo = $cache->getItem($cacheId, $success);
        if (!$success) {
            $flickr = $this->getFlickr();
            $groupInfo = $flickr->getGroupInfo($groupId);

            $cache->setItem($cacheId, $groupInfo);
        }
        return $groupInfo;
    }
    
    public function getFotos($flickr, $groupId, $page, $perPage = 20)
    {
        $normalizedGroupId = str_replace('@', 'at', $groupId);
        $cache = $this->getServiceLocator()->get('filesystem');
        $cacheId = 'grupoFotos-' . $normalizedGroupId . '-pag-' . $page;
        $fotos = $cache->getItem($cacheId, $success);
        if (!$success) {
            $flickr->getHttpClient()->setOptions(array(
                'sslverifypeer' => false,
                'timeout'       => 150
            ));
            
            try {
                $results = $flickr->groupPoolGetPhotos($groupId, array(
                    'page' => $page,
                    'per_page' => $perPage
                ));
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
                
                $cache->setItem($cacheId, $fotos);
            } catch (FlickrException $e) {
                echo $e->getMessage();
            }
            
        }  //!success
        return $fotos;  
    }
    
    public function getGroupTags($flickr, $fotos)
    {
        $fotoService = $this->getServiceLocator()->get('photo-service');
        $tagsString = '';
        $fotosConTag = array();
        foreach ($fotos as $foto) {
            $fotoInfo = $fotoService->getPhotoInfo($flickr, $foto['id']);
            $tagsString .= $fotoInfo['tags'];
            $fotosConTag[] = array(
                'id'     => $foto['id'],
                'tags'   => $fotoInfo['tags'],
                'catego' => '',
            );
            $foto['tags'] = $fotoInfo['tags'];
        }
        //\Zend\Debug\Debug::dump($fotos);die();
        $tagsArray = preg_split('/\s+/', $tagsString);
        $cuenta = array_count_values($tagsArray);
        arsort($cuenta);
        return array($cuenta, $fotosConTag);
    }
    
    public function getGroupCategories($tagsArray)
    {
        $categorias = array();
        foreach ($tagsArray as $key => $value){
            if ($value > 4) {
                $categorias[] = $key;
            }
            if ($value == 1) {
                break;
            }
        }
        return $categorias;
    }
    
    public function getPhotosCategories($photos, $categorias)
    {
        $fotosCategorizadas = array();
        foreach ($photos as $photo) {
            $fotosCategorizadas[$photo['id']] = '';
        }
        foreach ($photos as $photo) {
            $tagsArray = preg_split('/\s+/', $photo['tags']);
            foreach ($tagsArray as $tag) {
                if (in_array($tag, $categorias)){
                    if ($fotosCategorizadas[$photo['id']] == '' ) {
                        $fotosCategorizadas[$photo['id']] = $tag;
                    } else {
                        $fotosCategorizadas[$photo['id']] .= '-' . $tag;
                    }
                }
            }
        }
        return $fotosCategorizadas;
    }

    public function makeFotosTrio($fotos, $perPage = 20)
    {
            $retval = array();
            foreach ($fotos as $result) {
              
                    $retval[] = array(
                        'id'       => $result['id'],
                        'id2'      => '',
                        'id3'      => '',
                        'image1'   => $result['uri'],
                        'image2'   => '',
                        'image3'   => '',
                        'title'    => $result['title'],
                        'largeImg' => $result['largeImg']
                    ) ;
                
            }

    
            $i=0;
            $x1 = $perPage -1;
            $x2 = $perPage -2;
            for ($i = 0; $i<($perPage); $i++) {
                $retval[$i]['id2'] = $retval[$x1]['id'];
                $retval[$i]['id3'] = $retval[$x2]['id'];
                $retval[$i]['image2'] = $retval[$x1]['image1'];
                $retval[$i]['image3'] = $retval[$x2]['image1'];
                $x1 = $x1 - 1;
                $x2 = $x2 - 1;
                if ($x2 == 0) {
                    $x2 = $perPage-1;
                }
            }
            
        
    
        return $retval;
    }
    
}