<?php

namespace Routing\Configuration;
/**
 * Class Configuration
 * @package Routing\Configuration
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    private $routes = array();

    /**
     * Configuration constructor.
     * @param $routes
     */
    public function __construct(array $routes) {
        $this->routes = $routes ;
    }

    /**
     * Returns route set
     * @return array
     */
    public function getRoutes() {
        return $this->routes;
    }

}