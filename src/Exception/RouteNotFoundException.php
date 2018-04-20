<?php

namespace Routing\Exception;

/**
 * Class RouteNotFoundException
 * @package Routing\Exception
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class RouteNotFoundException extends \InvalidArgumentException
{

    /**
     * RouteNotFoundException constructor.
     * Call class parent construct
     * @param string $message
     */
    public function __construct($message) {
        parent::__construct($message, 404);
    }
}