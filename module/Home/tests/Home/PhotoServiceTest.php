<?php
use Home\Service\PhotoService;
use ZendService\Flickr\Flickr;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

class PhotoServiceTest extends AbstractControllerTestCase
{
    private $photoService;
    private $flickr;
    private $config;
    private $cacheDir;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->setApplicationConfig(
            include '/paginas/pueblapictures.com/config/application.config.php'
        );
        
        $config = include '/paginas/pueblapictures.com/config/autoload/flickr.local.php';
        $this->config = $config;
        
        $key     = $config['flickr']['key'];
        
        $flickr = new Flickr($key);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $this->flickr = $flickr;
        
        $this->cacheDir = '/paginas/pueblapictures.com/data/cache';
        
        $this->photoService = new PhotoService();
    }
    
    public function testGetPhotoDetails()
    {
        $id = '15639103582';
        $this->photoService->getPhotoDetails($this->flickr, $id, $this->cacheDir);
    }
    
    public function testGetPhotoTrio()
    {
        $id = '16274443098+15639103582+15683111516';
        $idarray = explode('+', $id);
        $photos = array();
        foreach ($idarray as $id) {
            $photos[] = array(
                'id'      => $id,
                'details' => $this->photoService->getPhotoDetails($this->flickr, $id, $this->cacheDir),
                'info'    => $this->photoService->getPhotoInfo($this->flickr, $id, $this->cacheDir),
                'exif'    => $this->photoService->getPhotoExif($this->flickr, $id, $this->cacheDir),
            );
        }
        echo '';
    }
}