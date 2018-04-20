<?php

namespace Routing\Core;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface Matchable
 * @package Routing\Core\Matcher
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
interface Matchable
{

    /**
     * Check if route math has the contained url in request
     * @param Route $route
     * @param Request $request
     * @return mixed
     */
    public function match(Route $route, Request $request);

    /**
     * Returns True if <code>Route</code> match url
     * Returns matches params
     * @return array matches parameters
     */
    public function getMatches();

}