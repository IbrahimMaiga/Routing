<?php

/**
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>.
 */


namespace Routing\Tests\Core;


use Routing\Core\Loader;
use Routing\Core\StdLoader;

/**
 * Class StdLoaderTest
 * @package Routing\Tests\Core
 */
class StdLoaderTest extends \PHPUnit_Framework_TestCase
{

    private $config = array();
    /**
     * @var Loader
     */
    private static $stdLoader;

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
        if (self::$stdLoader == null) {
            self::$stdLoader = new StdLoader(FIXTURE_PATH . '/config.php');
        }
        return self::$stdLoader;
    }
}