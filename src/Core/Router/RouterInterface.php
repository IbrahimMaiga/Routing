<?php

namespace Routing\Core;

/**
 * Interface RouterInterface
 * @package Routing\Core\Router
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
interface RouterInterface
{
    public function interpret();
    public function getCurrentRoute();
}