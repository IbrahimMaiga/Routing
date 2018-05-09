<?php

/**
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>.
 */

namespace Routing\Tests\Core;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;
use Routing\Core\Matcher;
use Routing\Core\Pattern;
use Routing\Core\Requirements;
use Routing\Core\Route;
use Routing\Exception\RequirementsException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MatcherTest
 * @package Routing\Tests\Core
 */
class MatcherTest extends TestCase
{

    /*
    * @var Matcher
    */
    private static $matcher;
    /*
     * @var Route
     */
    private static $route;

    /**
     * @var MockBuilder|Request
     */
    private $request;

    /**
     * @var Pattern
     */
    private $pattern;

    /**
     * @var Requirements
     */
    private $requirements;

    /**
     * @var array
     */
    private $defaults;
    /*
     * @var string
     */
    private $bar;

    /**
     * Initialize variable
     */
    protected function setUp()
    {
        $this->defaults = array(
            'controller' => 'Class\Namespace\FakeClass',
            'action' => 'fakeMethod'
        );

        $this->bar = 'route1';
        $this->pattern = new Pattern('/begin/{id}/{foo}/{bar}');
        $this->requirements = new Requirements(
            array(
                'id' => '\d+',
                'foo' => '\w+',
                'bar' => '\w+'
            )
        );

        $this->request = $this->getRequestMock();
        $this->getRequestMock()->method('getMethod')->willReturn('get');
        $this->getRequestMock()->method('getScheme')->willReturn('http');
    }

    /**
     * Check if route match url in <code>Request</code>
     * @throws \Exception
     */
    public function testMatchWithParams()
    {
        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1/foo/bar');
        $this->assertTrue($this->getMatcher()->match([$this->getRoute()], $this->request));
    }

    /**
     *
     * @throws \Exception
     */
    public function testNotMatchWithParams()
    {
        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1');
        $this->assertFalse($this->getMatcher()->match([$this->getRoute()], $this->request));
    }

    /**
     * @throws \Exception
     */
    public function testMatchWithoutParams()
    {
        $route = new Route($this->bar, new Pattern('begin/'), $this->defaults);
        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin');
        $this->assertTrue($this->getMatcher()->match([$route], $this->request));
    }

    /**
     * @throws \Exception
     */
    public function testCheckMissingRequirementsException()
    {
        $this->expectException(RequirementsException::class);
        $route = new Route($this->bar, $this->pattern,  $this->defaults,
            new Requirements(array(
                'id' => '\d+',
                'foo' => '\w+'
            ))
        );

        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1/foo/bar');
        $this->getMatcher()->match([$route], $this->request);
    }

    /**
     * @throws \Exception
     */
    public function testCheckRequirementsValueException()
    {
        $this->expectException(RequirementsException::class);
        $route = new Route($this->bar, $this->pattern, $this->defaults,
            new Requirements(array(
                'id' => '\d+',
                'foo' => '\w+',
                'bar' => '\d+'
            ))
        );

        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1/foo/bar');
        $this->getMatcher()->match([$route], $this->request);
    }

    /**
     * @throws \Exception
     */
    public function testCheckMethodNotExistException(){
        $this->expectException(\InvalidArgumentException::class);
        $route = new Route($this->bar, $this->pattern, $this->defaults,
            new Requirements(array(
                'id' => '\d+',
                'foo' => '\w+',
                'bar' => '\d+',
                'method' => 'begin'
            ))
        );

        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1/foo/bar');
        $this->getMatcher()->match([$route], $this->request);
    }

    /**
     * @throws \Exception
     */
    public function testCheckMethodException(){
        $this->expectException(\RuntimeException::class);
        $route = new Route($this->bar, $this->pattern, $this->defaults,
            new Requirements(array(
                'id' => '\d+',
                'foo' => '\w+',
                'bar' => '\d+',
                'method' => 'post'
            ))
        );

        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1/foo/bar');
        $this->getMatcher()->match([$route], $this->request);
    }

    /**
     * @throws \Exception
     */
    public function testCheckSchemeException(){
        $this->expectException(\RuntimeException::class);
        $route = new Route($this->bar, $this->pattern,  $this->defaults,
            new Requirements(array(
                'id' => '\d+',
                'foo' => '\w+',
                'bar' => '\w+',
                'scheme' => 'https'
            ))
        );

        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1/foo/bar');
        $this->getMatcher()->match([$route], $this->request);
    }

    /**
     * test matches
     * @throws \Exception
     */
    public function testGetMatches()
    {
        $this->getRequestMock()->method('getPathInfo')->willReturn('/begin/1/foo/bar');
        $this->getMatcher()->match([$this->getRoute()], $this->request);
        $params = [
            'controller' => 'Class\Namespace\FakeClass',
            'action' => 'fakeMethod',
            'params' => [1, 'foo', 'bar']
        ];
        $this->assertEquals(array_merge($params, ['route' => $this->getRoute()]), $this->getMatcher()->getMatches());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Request
     */
    private function getRequestMock() {
        if ($this->request === null) {
            $this->request = $this->getMockBuilder(Request::class)
                ->setMethods(['getMethod', 'getPathInfo', 'getScheme'])
                ->getMock();
        }
        return $this->request;
    }

    private function getMatcher() {
        if (self::$matcher == null) {
            self::$matcher = new Matcher();
        }
        return self::$matcher;
    }

    private function getRoute() {
        if (self::$route == null) {
            self::$route = new Route(
                $this->bar,
                $this->pattern,
                $this->defaults,
                $this->requirements
            );
        }
        return self::$route;
    }
}
