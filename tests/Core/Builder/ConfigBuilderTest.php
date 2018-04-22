<?php

/**
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>.
 */


namespace Routing\Tests\Core;


use PHPUnit\Framework\TestCase;
use Routing\Configuration\Configuration;
use Routing\Core\ConfigBuilder;
use Routing\Core\ConfigBuilderInterface;
use Routing\Core\XmlLoader;
use Routing\Exception\BuildException;

/**
 * Class ConfigBuilderTest
 * @package Routing\Tests\Core
 */
class ConfigBuilderTest extends TestCase
{

    private static $configBuilder;
    private $xmlLoader;

    public function setUp()
    {
        $this->xmlLoader = new XmlLoader(FIXTURE_PATH . '/config.xml');
    }

    public function testGetConfiguration()
    {
        $this->assertInstanceOf(Configuration::class, $this->getBuilder()->getConfiguration());
    }

    public function testBuildException()
    {
        $this->getConfigBuilderMock()->getConfiguration();
    }

    private function getBuilder() {
        if (self::$configBuilder == null) {
            self::$configBuilder = new ConfigBuilder($this->xmlLoader);
        }
        return self::$configBuilder;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ConfigBuilderInterface
     */
    private function getConfigBuilderMock() {
        $this->expectException(BuildException::class);
        $configBuilder = $this->getMockBuilder(ConfigBuilder::class)
            ->setConstructorArgs([$this->xmlLoader])
            ->setMethods(['build'])->getMock();

        $configBuilder->method('build')->willReturn(false);
        return $configBuilder;
    }
}