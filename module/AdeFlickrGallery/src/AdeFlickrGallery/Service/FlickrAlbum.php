<?php
namespace AdeFlickrGallery\Service;

use ZendService\Flickr\Exception;
use AdeFlickrGallery\Service\FlickrPhoto;
use DOMDocument;
use DOMXPath;
use Zend\Http\Request as HttpRequest;
use Zend\Dom\Query;
use Zend\Dom\NodeList;

class FlickrAlbum extends FlickrPhoto
{
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
            'photos'  => $xalbum->getAttribute('photos'),
        );
        
        foreach ($xalbum->childNodes as $item) {
            if ($item->nodeName <> '#text') {
                $retval[$item->nodeName] = $item->nodeValue;
            }
        }
        
        return $retval;
    }
}