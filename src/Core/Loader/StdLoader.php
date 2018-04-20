<?php

namespace Routing\Core;

/**
 * Class StdLoader
 * @package Routing\Core\Loader
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class StdLoader implements Loader
{

    /**
     * @var string
     */
    private $path;

    /**
     * StdLoader constructor.
     * @param $path
     */
    public function __construct($path) {
        $this->path = $path;
    }

    /**
     * Method used to load a configuration identified in a file.
     * @return bool|mixed
     */
    public function load() {
        return require_once "$this->path";
    }
}