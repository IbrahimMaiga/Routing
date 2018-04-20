<?php

namespace Routing\Core;

/**
 * Interface RouteBuilderInterface
 * @package Routing\Core\Builder
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
interface RouteBuilderInterface extends Builder
{
    public function getRoute();
}