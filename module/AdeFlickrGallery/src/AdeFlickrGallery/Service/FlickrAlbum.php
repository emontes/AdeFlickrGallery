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

class FlickrAlbum extends FlickrPhoto
{
    
    public function getAlbumPhotos($albumId, $options)
    {
        static $method = 'flickr.photosets.getPhotos';
        static $defaultOptions = array('per_page'       => 20,
                                       'page'           => 1,
                                       'privacy_filter' => 1,
                                       'extras'         => 'license, date_upload, date_taken, owner_name, icon_server');
        if (empty($albumId) || !is_string($albumId)) {
            throw new Exception\InvalidArgumentException('You must supply an Album id');
        }
        
        $options['photoset_id'] = $albumId;
        $options = $this->prepareOptions($method, $options, $defaultOptions);
        
        // now search for photos
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
        
        $fotos = $xpath->query('//photo');
        $fotoService = new FotoService();
        $retval = array();
        foreach ($fotos as $foto) {
            $id = $foto->getAttribute('id');
            $retval[] = array(
                'id'      => $id,
                'title'   => $foto->getAttribute('title'),
                'details' => $fotoService->getPhotoDetails($this, $id),
            );
        }
        
       // $outXML = $dom->saveXML();
       return $retval;
        
    }
    
    public function getAlbumInfo($id) {
        static $method = 'flickr.photosets.getInfo';
        
        
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply an Album(set) id');
        }
        
        $id_text = 'photoset_id';
        $dom = $this->getDom($id, $id_text, $method);
        
        $xpath = new DOMXPath($dom);
        
        
        $xalbum = $xpath->query('//photoset')->item(0);
        
        $retval = array(
            'owner'   => $xalbum->getAttribute('owner'),
            'primary' => $xalbum->getAttribute('primary'),
            'count_photos'  => $xalbum->getAttribute('count_photos'),
        );
        
        foreach ($xalbum->childNodes as $item) {
            if ($item->nodeName <> '#text') {
                $retval[$item->nodeName] = $item->nodeValue;
            }
        }
        
        return $retval;
    }
}