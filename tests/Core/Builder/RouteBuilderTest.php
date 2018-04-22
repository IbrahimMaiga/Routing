<?php

/**
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>.
 */


namespace Routing\Tests\Core;


use PHPUnit\Framework\TestCase;
use Routing\Core\Pattern;
use Routing\Core\Requirements;
use Routing\Core\Route;
use Routing\Core\RouteBuilder;
use Routing\Core\RouteBuilderInterface;
use Routing\Exception\BuildException;

/**
 * Class RouteBuilderTest
 * @package Routing\Tests\Core
 */
class RouteBuilderTest extends TestCase
{

    /**
     * @var RouteBuilder
     */
    private $routeBuilder;

    public function setUp()
    {
        $array = [
            'name' => 'route1',
            'pattern' => [
                'value' => '/begin/{id}/{foo}/{bar}',
            ],
            'defaults' => 'Class\Namespace\FakeClass:fakeMethod',
            'requirements' => [
                'id' => '\d+',
                'foo' => '\w+',
                'bar' => '\w+',
            ]
        ];
        $this->routeBuilder = new RouteBuilder($array);
    }

    public function testGetRequirements()
    {
        $this->assertInstanceOf(Requirements::class, $this->routeBuilder->getRequirements());
    }

    public function testGetPattern()
    {
        $this->assertInstanceOf(Pattern::class, $this->routeBuilder->getPattern());
    }

    public function testGetNullPattern()
    {
        $routeBuilder = new RouteBuilder();
        $this->assertNull($routeBuilder->getPattern());
    }

    public function testGetDefaults()
    {
        $this->assertNotNull($this->routeBuilder->getDefaults());
    }

    public function testGetRoute()
    {
        $this->assertInstanceOf(Route::class, $this->routeBuilder->getRoute());
    }

    public function testBuildException()
    {
        $this->expectException(BuildException::class);
        $this->getRouteBuilderMock()->getRoute();
    }

    public function testBuildFalse()
    {
        $routeBuilder = new RouteBuilder(null);
        $this->assertFalse($routeBuilder->build());
    }

    public function testEmptyDefaults()
    {
        $routeBuilder = new RouteBuilder(null);
        $this->assertEmpty($routeBuilder->getDefaults());
    }

    public function testGetDefaultsIsClosure()
    {
        $array = array(
            'bar' => 'route1',
            'pattern' => [
                'value' => 'begin/{id}/{foo}/{bar}',
            ],
            'defaults' => function () {
                echo 'Hi!';
            },
            'requirements' => [
                'id' => '\d+',
                'foo' => '\w+',
                'bar' => '\w+',
            ]
        );
        $routeBuilder = new RouteBuilder($array);
        $this->assertArrayHasKey('callback', $routeBuilder->getDefaults());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|RouteBuilderInterface
     */
    private function getRouteBuilderMock()
    {
        $routeBuilder = $this->getMockBuilder(RouteBuilder::class)->setConstructorArgs([])
            ->setMethods(['build'])
            ->getMock();
        $routeBuilder->method('build')->willReturn(false);
        return $routeBuilder;
    }
}