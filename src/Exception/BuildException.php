<?php

namespace Routing\Exception;


/**
 * Class BuildException
 * @package Routing\Exception
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class BuildException extends \RuntimeException
{
    /**
     * BuildException constructor.
     * @param int $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }



}