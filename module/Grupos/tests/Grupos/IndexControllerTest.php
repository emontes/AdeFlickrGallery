<?php
use Grupos\Controller\IndexController;
use ZendService\Flickr\Flickr;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * IndexController test case.
 */
class IndexControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
//         parent::setUp();
        
        $this->setApplicationConfig(
            include '/paginas/pueblapictures.com/config/application.config.php'
        );
//         $this->IndexController = new IndexController(/* parameters */);

    }
    
    public function testGetServiceLocator()
    {
        $sm = $this->getApplicationServiceLocator();
        
        $config = $sm->get('config');
        $this->assertArrayHasKey('router',$config);
    }
    
    public function testGetGroupInfo()
    {
        $config = include '/paginas/pueblapictures.com/config/autoload/flickr.local.php';
        $key     = $config['flickr']['key'];
        $groupId = $config['flickr']['groups']['turistapuebla']['id'];
        $flickr = new Flickr($key);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $groupInfo = $flickr->getGroupInfo($groupId);
        $this->assertArrayHasKey('name', $groupInfo);
    }
    
    public function testGetGroupPoolGetPhotos()
    {
        $config = include '/paginas/pueblapictures.com/config/autoload/flickr.local.php';
        $key     = $config['flickr']['key'];
        $groupId = $config['flickr']['groups']['turistapuebla']['id'];
        $flickr = new Flickr($key);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $groupPhotos = $flickr->groupPoolGetPhotos($groupId, array('page' => 1, 'per_page' => 20));
        $this->assertAttributeGreaterThan(2, 'totalResultsAvailable', $groupPhotos);
    }

    
    public function testIndexActionCanBeAccessed()
    {
       
        
//         $this->dispatch('/grupos/turistapuebla');
//         $this->assertResponseStatusCode(200);
    
//         $this->assertModuleName('Album');
//         $this->assertControllerName('Album\Controller\Album');
//         $this->assertControllerClass('AlbumController');
//         $this->assertMatchedRouteName('album');
    }
    
}

