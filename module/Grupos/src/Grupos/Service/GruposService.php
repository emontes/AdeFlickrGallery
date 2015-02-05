<?php
namespace Grupos\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Debug\Debug;

class GruposService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator() {
        return $this->serviceLocator;
    }
    
    public function getGroupInfo($flickr, $groupId)
    {
        $cache = $this->getServiceLocator()->get('filesystem');
        $normalizedGroupId = str_replace('@', 'at', $groupId);
        $cacheId = 'grupoInfo-' . $normalizedGroupId;
        $groupInfo = $cache->getItem($cacheId, $success);
        if (!$success) {
            $groupInfo = $flickr->getGroupInfo($groupId);

            $cache->setItem($cacheId, $groupInfo);
        }
        return $groupInfo;
    }
    
    public function getFotos($flickr, $id, $groupId, $page)
    {
        $cache = $this->getServiceLocator()->get('filesystem');
        $cacheId = 'grupoFotos-' . $id . '-pag-' . $page;
        $fotos = $cache->getItem($cacheId, $success);
        if (!$success){
    
            $flickr->getHttpClient()->setOptions(array(
                'sslverifypeer' => false,
                'timeout'       => 150
            ));
                $perPage = 20;
            
            
    
            $results = $flickr->groupPoolGetPhotos($groupId, array(
                'page' => $page,
                'per_page' => $perPage
            ));
            
           
            $fotos = array();
            foreach ($results as $result) {
//                                                     $debug = new Debug();
//                                                     $debug->dump($result);
//                                                     die();
                if (isset($result->Medium)) {
                    if (isset($result->title)){
                        $title = $result->title;
                    } else {
                        $title = '';
                    }
                    if (isset($result->Large)) {
                        $largeImg = $result->Large->uri;
                    } else {
                        $largeImg = '';
                    }
                    $fotos[] = array(
                        'id'       => $result->id,
                        'image1'   => $result->Medium->uri,
                        'image2'   => '',
                        'image3'   => '',
                        'title'    => $title,
                        'largeImg' => $largeImg,
                    ) ;
                }
            }

    
            $i=0;
            $x1 = $perPage -1;
            $x2 = $perPage -2;
            for ($i = 0; $i<($perPage); $i++) {
                 
                $fotos[$i]['image2'] = $fotos[$x1]['image1'];
                $fotos[$i]['image3'] = $fotos[$x2]['image1'];
                $x1 = $x1 - 1;
                $x2 = $x2 - 1;
                if ($x2 == 0) {
                    $x2 = $perPage-1;
                }
            }
            $cache->setItem($cacheId, $fotos);
        }
    
        return $fotos;
    }
    
}