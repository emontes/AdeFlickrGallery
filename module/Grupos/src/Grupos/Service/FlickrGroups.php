<?php
namespace Grupos\Service;

use ZendService\Flickr\Flickr;
use ZendService\Flickr\Exception;
use DOMDocument;
use DOMXPath;
use Zend\Http\Request as HttpRequest;
use Zend\Feed\Reader\Collection;

class FlickrGroups extends Flickr
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
        
        $dom = $this->getDom($id, $method);
        self::checkErrors($dom);
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
        $dom = $this->getDom($id, $method);
        self::checkErrors($dom);
        $xpath = new DOMXPath($dom);
        $topics = array();
        $retval = array();
        foreach ($xpath->query('//topic') as $topic) {
           $label = (string) $topic->getAttribute('id');
           $retval[$label] = $topic->getAttribute('subject');
        }
        return $retval;
        
    }
    
    private function getDom($id, $method)
    {
        $options = array('api_key' => $this->apiKey, 'method' => $method, 'group_id' => (string)$id);
        
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a Group id');
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
        return $dom;
    }
    
    
    
}