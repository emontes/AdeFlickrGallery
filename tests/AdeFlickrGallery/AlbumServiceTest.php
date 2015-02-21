<?php
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use AdeFlickrGallery\Service\AlbumService;
use AdeFlickrGallery\Service\FlickrAlbum;

class AlbumServiceTest extends AbstractControllerTestCase
{
    private $albumService;
    private $config;
    private $flickr;
    private $cacheDir;
    
    protected function setUp()
    {
        parent::setUp();
        $this->setApplicationConfig(
            include '/paginas/pueblapictures.com/config/application.config.php'
        );
        $this->cacheDir = '/paginas/pueblapictures.com/data/cache';
        $this->config = include '/paginas/pueblapictures.com/config/autoload/flickr.local.php';
        $this->flickr = new FlickrAlbum($this->config['flickr']['key']);
        $this->flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $this->albumService = new AlbumService();
    }
    
    function testGetFotos()
    {
        $fotos = $this->albumService->getFotos($this->flickr, '72157628763825895', 1, 20, $this->cacheDir);
        echo 'pato';
    }
    
    function testGetAlbumInfo()
    {
        $albumInfo = $this->albumService->getAlbumInfo($this->flickr, '72157628763825895', $this->cacheDir);
        $this->assertArrayHasKey('title', $albumInfo);
    }
}