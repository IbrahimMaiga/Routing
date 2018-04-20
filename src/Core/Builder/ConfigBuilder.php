<?php


namespace Routing\Core;


use Routing\Configuration\Configuration;
use Routing\Configuration\ConfigurationInterface;
use Routing\Exception\BuildException;

/**
 * Class ConfigBuilder
 * @package Routing\Core
 */
class ConfigBuilder implements ConfigBuilderInterface
{

    /**
     * @var RouteBuilder
     */
    private $routeBuilder;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * ConfigBuilder constructor.
     * @param RouteBuilderInterface $routeBuilder
     * @param Loader $loader
     */
    public function __construct(Loader $loader, RouteBuilderInterface $routeBuilder = null)
    {
        $this->routeBuilder = $routeBuilder ?: new RouteBuilder();
        $this->loader = $loader;
        $this->data = $loader->load();
    }

    /**
     * Routes collection creation
     * @return array
     */
    private function buildRoutes()
    {
        return array_map([$this, 'map'], array_keys($this->data), array_values($this->data));
    }

    private function map($key, $value)
    {
        if (!array_key_exists('name', $value)) {
            $value['name'] = $key;
        }
        return $this->routeBuilder->setData($value)->getRoute();
    }

    /**
     * @return bool
     */
    public function build()
    {
        if (isset($this->data)) {
            $this->configuration = new Configuration($this->buildRoutes());
            return true;
        }
        return false;
    }

    /**
     * Build Configuration instance
     * @return ConfigurationInterface
     * @throws BuildException
     */
    public function getConfiguration()
    {
        if ($this->build()) {
            return $this->configuration;
        }
        throw new BuildException("Error when creating object, check data format");
    }
}