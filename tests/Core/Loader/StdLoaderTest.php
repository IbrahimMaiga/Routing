<?php

/**
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>.
 */


namespace Routing\Tests\Core;


use Routing\Core\StdLoader;

/**
 * Class StdLoaderTest
 * @package Routing\Tests\Core
 */
class StdLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    private $configWithBracket = array();

    /**
     * @var array
     */
    private $configWithColon = array();

    public function setUp()
    {
        $this->configWithBracket = [
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

        $this->configWithColon = [
            'route1' => [
                'pattern' => [
                    'value' => '/begin/:id/:foo/:bar',
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

    public function testLoadPatternWithBracket()
    {
        $this->assertSame($this->configWithBracket, $this->getLoader('config')->load());
    }

    public function testLoadPatternWithColon()
    {
        $this->assertSame($this->configWithColon, $this->getLoader('config_wc')->load());
    }


    private function getLoader($config)
    {
        return new StdLoader(FIXTURE_PATH . "/$config.php");
    }
}