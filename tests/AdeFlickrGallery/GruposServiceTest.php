<?php
use AdeFlickrGallery\Service\GruposService;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use AdeFlickrGallery\Service\FlickrGroups;

/**
 * GruposService test case.
 */
class GruposServiceTest extends AbstractControllerTestCase
{

    /**
     *
     * @var GruposService
     */
    private $GruposService;
    private $flickr;
    private $config;
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
        
        $config = include '/paginas/pueblapictures.com/config/autoload/flickr.local.php';
        $this->config = $config;
        
        $key     = $config['flickr']['key'];
        
        $flickr = new FlickrGroups($key);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $this->flickr = $flickr;
        
        $this->cacheDir = '/paginas/pueblapictures.com/data/cache';
        $this->GruposService = new GruposService();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        
        $this->GruposService = null;
        
        parent::tearDown();
    }
    
    public function testGetGroupInfo() 
    {
        $groupId = $this->config['flickr']['groups']['turistapuebla']['id'];
        $groupInfo = $this->GruposService->getGroupInfo($this->flickr, $groupId, $this->cacheDir);
        $this->assertArrayHasKey('name', $groupInfo);
        $this->assertArrayHasKey('description', $groupInfo);
        $this->assertArrayHasKey('members', $groupInfo);
        $this->assertArrayHasKey('pool_count', $groupInfo);
    }
  

    /**
     * Tests GruposService->getFotos()
     */
    public function testGetFotos()
    {
        $groupId = $this->config['flickr']['groups']['turistapuebla']['id'];
        $this->GruposService->getFotos($this->flickr, $groupId, 1 , 3, $this->cacheDir);
    }

    /**
     * Tests GruposService->getGroupTags()
     */
    public function testGetGroupTags()
    {
        // TODO Auto-generated GruposServiceTest->testGetGroupTags()
        $this->markTestIncomplete("getGroupTags test not implemented");
        
        $this->GruposService->getGroupTags(/* parameters */);
    }

    /**
     * Tests GruposService->getGroupCategories()
     */
    public function testGetGroupCategories()
    {
        $tags = array(
            'puebla' => 20,
            'méxico' => 8 , 
            'gx10' => 6,
            'nocturna' => 5,
        );
        $this->GruposService->getGroupCategories($tags);
    }

    /**
     * Tests GruposService->getPhotosCategories()
     */
    public function testGetPhotosCategories()
    {
         $tags = array(
            'puebla',
            'méxico', 
            'gx10',
            'nocturna',
        );
        $fotosConTags = array(
            array (
                'id'    => '1',
                'tags'   => 'puebla',
                'catego' => ''  
            ),
            array (
                'id'    => '2',
                'tags'   => 'puebla hoteles',
                'catego' => ''
            ),
            array (
                'id'    => '2',
                'tags'   => 'nocturna gx10',
                'catego' => ''
            ),
            array (
                'id'    => '3',
                'tags'   => 'puebla',
                'catego' => ''
            ),
            array (
                'id'    => '4',
                'tags'   => 'puebla nikon gx10 méxico',
                'catego' => ''
            ),
        );
        $result = $this->GruposService->getPhotosCategories($fotosConTags, $tags);
    }

    /**
     * Tests GruposService->makeFotosTrio()
     */
    public function testMakeFotosTrio()
    {
        // TODO Auto-generated GruposServiceTest->testMakeFotosTrio()
        $this->markTestIncomplete("makeFotosTrio test not implemented");
        
        $this->GruposService->makeFotosTrio(/* parameters */);
    }
}

