<?php
namespace AdeFlickrGallery\Service;

use ZendService\Flickr\Exception;
use AdeFlickrGallery\Service\FlickrPhoto;
use AdeFlickrGallery\Service\FotoService;
use DOMDocument;
use DOMXPath;
use Zend\Http\Request as HttpRequest;
use Zend\Dom\Query;
use Zend\Dom\NodeList;



class FlickrCollections extends FlickrPhoto
{
    /**
     * Returns a tree (or sub tree) of collections belonging to a given user.
     *
     * @param  string $userId
     * @param  string $collectionId
     * @return ResultSet
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function getColletions($userId=null, $collectionId=null)
    {
        static $method = 'flickr.collections.getTree';
    
        $options = array(
            'api_key' => $this->apiKey,
            'method' => $method,
            'user_id' => $userId,
            'collection_id' => (string)$collectionId);
    
      
        $request = new HttpRequest;
        $request->setUri(self::URI_BASE);
        $request->getQuery()->fromArray($options);
        $response = $this->httpClient->send($request);
    
        if ($response->isServerError() || $response->isClientError()) {
            throw new Exception\RuntimeException('An error occurred sending request. Status code: '
                . $response->getStatusCode());
        }
    
        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());
        self::checkErrors($dom);
        return $dom;
    }
    
    public function getCollectionInfo($userId, $collectionId)
    {
        $collections = $this->getColletions($userId, $collectionId);;
        $xpath = new DOMXPath($collections);
        $item = $xpath->query('//collection')->item(0);
        
        $retval = array(
            'id'          => $item->getAttribute('id'),
            'title'       => $item->getAttribute('title'),
            'description' => $item->getAttribute('description'),
            'iconlarge'   => $item->getAttribute('iconlarge'),
        );
        
        foreach ($item->childNodes as $child) {
            if ($child->nodeName <> '#text') {
                $link = '/coleccion/';
                $icon = $child->getAttribute('iconlarge');
                if ($child->nodeName == 'set') {
                    $link = '/album/';
                    $icon = $this->getCollectionAlbumIcon($child->getAttribute('id'));
                }
                $childs[] = array(
                    'title'       => $child->getAttribute('title'),
                    'description' => $child->getAttribute('description'),
                    'icon'   => $icon,
                    'link'   => $link . $child->getAttribute('id') 
                );
            }
        }
        
        $retval['childs'] = $childs;
            
        
        return $retval;
    }
    
    public function getCollectionAlbumIcon ($albumId)
    {
        $fotoService = new FotoService();
        static $method = 'flickr.photosets.getInfo';
        $id_text = 'photoset_id';
        $dom = $this->getDom($albumId, $id_text, $method);
        $xpath = new DOMXPath($dom);
        $xalbum = $xpath->query('//photoset')->item(0);
        $primaryId = $xalbum->getAttribute('primary');
        $details = $fotoService->getPhotoDetails($this, $primaryId);
        if ($details['medium'] <> null) {
            $uri = $details['medium']->uri;
        } else {
            $uri = $details['original']->uri;
        }
        return $uri;
    }
    
    public function getColletionTreeMenu($userId, $collectionId)
    {
        $collections = $this->getColletions($userId, $collectionId);

        $xpath = new DOMXPath($collections);
        $nodos = $xpath->query('//collection')->item(0);
        $retval = $this->getCollectionPages($nodos);
                
        return $retval;
    }
    
    private function getCollectionPages($item)
    {
        
        //$xml = $item->ownerDocument->saveXML($item); //para debugear el contenido
        $link = '/coleccion/';
        if ($item->nodeName == 'set') {
            $link = '/album/';
        }
        $retval = array(
            'label' => $item->getAttribute('title'),
            'uri'   => $link . $item->getAttribute('id'),
        );
        $pages = array();
        $childNodes = $item->childNodes;
        foreach ($childNodes as $child) {
            if ($child->nodeName <> '#text') {
                
                $pages[] = $this->getCollectionPages($child);
            }
        }
        if (count($pages > 0)) {
            $retval['pages'] = $pages;
        }
        
        

        return $retval;
    }
    
  
    
    
    
}