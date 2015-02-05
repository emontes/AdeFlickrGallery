<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Galerias for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Galerias\Controller;

use ZendService\Flickr\Flickr;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    
    public function indexAction()
    {
       
        $flickr = new Flickr($key);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        
        $collections = $flickr->collecionGetTree($userId, $collectionId);
        
//         $fotos = $flickr->groupPoolGetPhotos($groupId, array(
//             'page' => 1,
//             'per_page' => 10
//         ));
        return array(
            'collections' => $collections
        );
    }

}
