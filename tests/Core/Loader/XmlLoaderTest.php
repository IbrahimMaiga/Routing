<?php

/**
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>.
 */

namespace Routing\Tests\Core;

use Routing\Core\Loader;
use Routing\Core\XmlLoader;

/**
 * Class XmlLoaderTest
 * @package Routing\Tests\Core
 */
class XmlLoaderTest extends \PHPUnit_Framework_TestCase
{

    private $config = array();
    /**
     * @var Loader
     */
    private static $xmlLoader;


    public function setUp()
    {
        $this->config = [
            'route1' => [
                'pattern' => [
                    'value' => '/begin/{id}/{foo}/{bar}',
                ],
                'defaults' => 'Class\Namespace\FakeClass:fakeMethod',
                'requirements' => [
                    'id' => '\d+',
                    'foo' => '\w+',
                    'bar' => '\w+',
                ]
            ]
        ];
    }

    public function testLoad()
    {
        $this->assertSame($this->config, $this->getLoader()->load());
    }

    private function getLoader()
    {
        if (self::$xmlLoader == null) {
            self::$xmlLoader = new XmlLoader(FIXTURE_PATH . '/config.xml');
        }
        return self::$xmlLoader;
    }
}