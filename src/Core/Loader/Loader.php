<?php


namespace Routing\Core;

/**
 * Interface Loader
 * @package Routing\Loader
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
interface Loader
{

    /**
     * Method used to load a configuration identified in a file.
     * @return mixed
     */
    public function load();
}