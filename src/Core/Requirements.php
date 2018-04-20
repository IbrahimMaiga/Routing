<?php

namespace Routing\Core;

use Routing\Exception\RequirementsException;

/**
 * Class Requirement
 * @package Routing\Core
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class Requirements
{
    /**
     * @var array
     */
    private static $httpSpecifiesKeys = ['method', 'scheme'];

    /**
     * @var string
     * @noinspection PhpUnusedPrivateFieldInspection
     */
    private static $defaultMethod = 'get';

    /**
     * @var string
     * @noinspection PhpUnusedPrivateFieldInspection
     */
    private static $defaultScheme = 'http';

    /**
     * @var array
     */
    private $specifies;

    /**
     * @var array
     */
    private $urlSpecifies;

    /**
     * @var array
     */
    private $httpSpecifies = [];

    /**
     * Requirement constructor.
     * @param array $specifies
     */
    public function __construct($specifies = [])
    {
        $this->resolveHttpSpecifies(self::$httpSpecifiesKeys, $specifies);
        $this->urlSpecifies = $specifies;
        $this->specifies = array_merge($this->urlSpecifies, $this->httpSpecifies);
    }

    private function resolveHttpSpecifies(array $names, array &$specifies)
    {
        foreach ($names as $name) {
            if ($this->accept($name)) {
                if (!isset($specifies[$name])) {
                    $ucFirstName = ucfirst($name);
                    $this->httpSpecifies[$name] = self::${"default$ucFirstName"};
                } else {
                    $this->httpSpecifies[] = $specifies[$name];
                    unset($specifies[$name]);
                }
            } else {
                throw new RequirementsException(sprintf('%s is not an accepted key'));
            }
        }
    }

    private function accept(string $name)
    {
        return in_array($name, self::$httpSpecifiesKeys);
    }

    /**
     * @param $name
     * @param $regex
     * @return $this current instance
     */
    public function specify($name, $regex)
    {
        $this->specifies[$name] = $regex;

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->specifies[$name];
    }

    /**
     * Returns requirements keys
     * @return array
     */
    public function get_keys()
    {
        return array_keys($this->specifies);
    }

    public function getSpecifies()
    {
        return $this->specifies;
    }

    public function has($key)
    {
        return isset($this->specifies[$key]);
    }

    public function getUrlSpecifiesKeys()
    {
        return array_keys($this->urlSpecifies);
    }
}