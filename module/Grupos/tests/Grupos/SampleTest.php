<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Galerias for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace GruposTest;
use Grupos\Controller\IndexController;

class SampleTest extends Framework\TestCase
{
    private $indexController;
    
    protected function setUp()
    {
        $this->indexController = new IndexController();
    }

    public function testSample()
    {
        $this->assertInstanceOf('Zend\Di\LocatorInterface', $this->getLocator());
    }
    
    public function testGetConfig()
    {
        $sl = $this->indexController->getServiceLocator();
        $config = $sl->get('config');
        $this->assertArrayHasKey('flickr', $config);
    }
    
    public function testIndexActionCanBeAccessed()
    {
//         $this->dispatch('/grupos');
//         $this->assertResponseStatusCode(200);
    }
}
