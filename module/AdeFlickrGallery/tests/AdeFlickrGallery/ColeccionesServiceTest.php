<?php
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use AdeFlickrGallery\Service\ColeccionesService;
use AdeFlickrGallery\Service\FlickrCollections;

class ColeccionesServiceTest extends AbstractControllerTestCase
{
    private $coleccionesService;
    private $cacheDir;
    private $config;
    private $userId;
    private $collectionId;
    
    protected function setUp()
    {
        parent::setUp();
        $this->setApplicationConfig(
            include '/paginas/pueblapictures.com/config/application.config.php'
        );
        $config = include '/paginas/pueblapictures.com/config/autoload/flickr.local.php';
        $this->config = $config;
        $key     = $this->config['flickr']['key'];
        $flickr = new FlickrCollections($key);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $this->flickr = $flickr;
        $this->cacheDir = '/paginas/pueblapictures.com/data/cache';
        $this->coleccionesService = new ColeccionesService();
        $this->userId = $this->config['flickr']['user_id'];
        $this->collectionId = $this->config['flickr']['collections']['puebla'];
    }
    
    public function testGetCollectionInfo()
    {
        $collectionInfo = $this->coleccionesService->getCollectionInfo(
            $this->flickr, $this->userId, $this->collectionId);
        echo '';
    }
    
    public function testGetCollectionTreeMenu()
    {
        $collectionTree = $this->coleccionesService->getCollectionTreeMenu(
            $this->flickr, $this->userId, $this->collectionId, $this->cacheDir);
        $this->assertArrayHasKey('label', $collectionTree);
        
    }
    
    
}