<?php

/**
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>.
 */


namespace Routing\Tests\Core;


use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;
use Routing\Core\ConfigBuilder;
use Routing\Core\Matcher;
use Routing\Core\Router;
use Routing\Core\XmlLoader;
use Routing\Exception\RequirementsException;
use Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RouterTest
 * @package Routing\Tests\Core
 */
class RouterTest extends TestCase
{

    /**
     * @var Router
     */
    private $router;
    private $matcher;
    private $configBuilder;

    /**
     * @var MockBuilder
     */
    private static $request;

    public function setUp()
    {
        $this->matcher = new Matcher();
        $this->configBuilder = new ConfigBuilder(new XmlLoader(FIXTURE_PATH . '/config.xml'));
        $this->getRequestMock()->method('getMethod')->willReturn('get');
        $this->getRequestMock()->method('getScheme')->willReturn('http');
        $this->router = new Router($this->configBuilder, $this->matcher, $this->getRequestMock());
    }

    public function testInterpretMatch()
    {
        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1/foo/bar');
        $matches = [
            'class' => 'Class\Namespace\FakeClass',
            'action' => 'fakeMethod',
            'params' => [1, 'foo', 'bar']
        ];
        $this->assertEquals($matches, $this->router->interpret());
    }

    public function testInterpretRouteNotFound() {
        $this->expectException(RouteNotFoundException::class);
        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1');
        $this->assertEquals(false, $this->router->interpret());
    }

    public function testGenerate() {
        $this->getRequestMock()->method('getHost')->willReturn('localhost');
        $params = array('id' => 1, 'foo' => 'foo', 'bar' => 'bar');
        $url = $this->router->generate('route1', $params);
        $this->assertEquals($url, '/begin/1/foo/bar');
    }

    public function testGenerateIncorrectRequirementValueException() {
        $params = array('id' => 'Kanfa', 'foo' => 'foo', 'bar' => 'bar');
        $this->expectException(RequirementsException::class);
        $this->router->generate('route1', $params);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getRequestMock() {
        if (self::$request == null) {
            self::$request = $this->getMockBuilder(Request::class)
                ->setMethods(['getMethod', 'getPathInfo','getScheme', 'getHost'])
                ->getMock();
        }
        return self::$request;
    }
}