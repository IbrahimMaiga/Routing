<?php

namespace Routing\Configuration;

/**
 * Interface ConfigurationInterface
 * @package Routing\Configuration
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
interface ConfigurationInterface extends Configurable
{
    public function getRoutes();
}