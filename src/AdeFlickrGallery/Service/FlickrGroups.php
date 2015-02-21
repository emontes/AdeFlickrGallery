<?php
namespace AdeFlickrGallery\Service;

use ZendService\Flickr\Exception;
use AdeFlickrGallery\Service\FlickrPhoto;
use DOMDocument;
use DOMXPath;
use Zend\Http\Request as HttpRequest;
use Zend\Feed\Reader\Collection;

class FlickrGroups extends FlickrPhoto
{
    /**
     * Get the Group Info as an array
     *
     * @param  string $id id of the group
     * @return array
     */
    public function getGroupInfo($id)
    {
        static $method = 'flickr.groups.getInfo';
        
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a Group id');
        }
        
        $dom = $this->getDom($id, 'group_id', $method);
        
        $xpath = new DOMXPath($dom);
        $group = $xpath->query('//group')->item(0);
        $retval = array();
        foreach ($group->childNodes as $item) {
            if ($item->nodeName <> '#text'){
                $retval[$item->nodeName] = $item->nodeValue;
            }
        
        }
        return $retval;
    }
    
    public function getTopicsList($id)
    {
        static $method = 'flickr.groups.discuss.topics.getList';
        
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a Group id');
        }
        
        $dom = $this->getDom($id, 'group_id', $method);
        
        $xpath = new DOMXPath($dom);
        $topics = array();
        $retval = array();
        foreach ($xpath->query('//topic') as $topic) {
           $label = (string) $topic->getAttribute('id');
           $retval[$label] = $topic->getAttribute('subject');
        }
        return $retval;
        
    }
    
}