<?php

namespace GruposTest;

use Grupos\Service\GruposService;
use ZendServiceTest\Flickr;
use ZendServiceTest\Flickr\OnlineTest;

/**
 * GruposService test case.
 */
class GruposServiceTest extends Framework\TestCase
{
    protected $serviceLocator;

    /**
     *
     * @var GruposService
     */
    private $GruposService;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        
        $this->GruposService = new GruposService(/* parameters */);
        //$this->flickr = new Flickr(constant('TESTS_ZEND_SERVICE_FLICKR_ONLINE_APIKEY'));
        
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->GruposService = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests GruposService->setServiceLocator()
     */
    public function testSetServiceLocator()
    {
        // TODO Auto-generated GruposServiceTest->testSetServiceLocator()
        $this->markTestIncomplete("setServiceLocator test not implemented");
        
        $this->GruposService->setServiceLocator(/* parameters */);
    }

    /**
     * Tests GruposService->getServiceLocator()
     */
    public function testGetServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Tests GruposService->getGroupInfo()
     */
    public function testGetGroupInfo()
    {
        
        
        //$this->GruposService->getGroupInfo('turistapuebla');
        
    }

    /**
     * Tests GruposService->getFotos()
     */
    public function testGetFotos()
    {
        // TODO Auto-generated GruposServiceTest->testGetFotos()
        $this->markTestIncomplete("getFotos test not implemented");
        
        $this->GruposService->getFotos(/* parameters */);
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
        $result = $this->GruposService->getGroupCategories($tags);
        $this->assertArrayHasKey(2, $result);
        
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
        $this->assertArrayHasKey(2, $result);
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

