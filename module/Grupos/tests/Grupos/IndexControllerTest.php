<?php
use Grupos\Controller\IndexController;
use Grupos\Service\GruposService;
use Grupos\Service\FlickrGroups;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * IndexController test case.
 */
class IndexControllerTest extends AbstractHttpControllerTestCase
{
    
    private $indexController;
    private $gruposService;
    private $config;
    private $flickr;
    private $cacheDir;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->setApplicationConfig(
            include '/paginas/pueblapictures.com/config/application.config.php'
        );
        
        $this->config = include '/paginas/pueblapictures.com/config/autoload/flickr.local.php';
        $key     = $this->config['flickr']['key'];
        
        $flickr = new FlickrGroups($key);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $this->flickr = $flickr;
        
        $this->cacheDir = '/paginas/pueblapictures.com/data/cache';
        
        $this->IndexController = new IndexController();
        $this->gruposService = new GruposService();

    }
    
    public function testGetTopicsList()
    {
        $groupId = $this->config['flickr']['groups']['puebla']['id'];
        $groupTopics = $this->gruposService->getGroupTopics($this->flickr, $groupId);
    }
    
    public function testGetGroupInfo()
    {
        $groupId = $this->config['flickr']['groups']['turistapuebla']['id'];
        $groupInfo = $this->flickr->getGroupInfo($groupId);
        $this->assertArrayHasKey('name', $groupInfo);
        $this->assertArrayHasKey('pool_count', $groupInfo);
        $perPage = 20;
        $pages = floor($groupInfo['pool_count'] / 20);
        $this->assertGreaterThan(1, $pages);
    }
    
    public function testGetGroupPoolGetPhotos()
    {
        $groupId = $this->config['flickr']['groups']['turistapuebla']['id'];
        $groupPhotos = $this->gruposService->getFotos($this->flickr, $groupId, 1, 2, $this->cacheDir);
        $this->assertArrayHasKey(0, $groupPhotos);
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

