<?php
namespace Foto\Service;

use ZendService\Flickr\Flickr;
use ZendService\Flickr\Exception;
use ZendService\Flickr\Image;
use DOMDocument;
use DOMXPath;
use Zend\Http\Request as HttpRequest;

use Zend\Feed\Reader\Collection;

class FlickrPhoto extends Flickr
{
    
    
    /**
     * Get the Photo Info as an array
     *
     * @param  string $id id of the photo
     * @return array
     */
    public function getPhotoInfo($id)
    {
        static $method = 'flickr.photos.getInfo';
    
        $options = array('api_key' => $this->apiKey, 'method' => $method, 'photo_id' => (string)$id);
    
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a Photo id');
        }
    
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
        $xpath = new DOMXPath($dom);
        $group = $xpath->query('//')->item(0);
        $retval = array();
        foreach ($group->childNodes as $item) {
            if ($item->nodeName <> '#text'){
                $retval[$item->nodeName] = $item->nodeValue;
            }
    
        }
        return $retval;
    }
    
    /**
     * Get the Photo Exifs as an array
     *
     * @param  string $id id of the photo
     * @return array
     */
    public function getPhotoExif($id)
    {
        static $method = 'flickr.photos.getExif';
    
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a Photo id');
        }
        
        $id_text = 'photo_id';
        $dom = $this->getDom($id, $id_text, $method);
    
        $xpath = new DOMXPath($dom);
    
        $photo = $xpath->query('//photo')->item(0);
         
        $retval = array();
        foreach ($photo->childNodes as $item) {
            if ($item->nodeName <> '#text'){
                $label = $item->getAttribute('label');
                foreach ($item->childNodes as $item2) {
                    if ($item2->nodeName <> '#text'){
                        $retval[$label] = $item2->nodeValue;
                    }
                }
            }
    
        }
         
        return $retval;
    }
    
    public function getImageInfoById($id)
    {
        static $method='flickr.photos.getInfo';
    
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a Photo id');
        }
    
        $id_text = 'photo_id';
        $dom = $this->getDom($id, $id_text, $method);
    
        $xpath = new DOMXPath($dom);
        $ximage = $xpath->query('//photo')->item(0);
        $retval = array();
        foreach ($ximage->childNodes as $item) {
            if ($item->nodeName <> '#text'){
                $retval[$item->nodeName] = $item->nodeValue;
            }
    
        }
        return $retval;
    }
    
    public function getDom($id, $id_text, $method)
    {
    
        $options = array('api_key' => $this->apiKey, 'method' => $method, $id_text => (string)$id);
    
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
    
}