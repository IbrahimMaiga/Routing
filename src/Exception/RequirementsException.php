<?php

namespace Routing\Exception;

/**
 * Class RequirementsException
 * @package Routing\Exception
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class RequirementsException extends \RuntimeException
{

    /**
     * RequirementsException constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}