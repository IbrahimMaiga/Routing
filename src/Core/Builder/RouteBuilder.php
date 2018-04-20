<?php

namespace Routing\Core;

use Routing\Exception\BuildException;

/**
 * Class RouteBuilder
 * @package Routing\Core\Builder
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class RouteBuilder implements RouteBuilderInterface
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * @var Route
     */
    private $route;

    /**
     * RouteBuilder constructor.
     * @param array $data
     */
    public function __construct($data = []) {
        $this->data = $data;
    }

    /**
     * @return Requirements
     */
    public function getRequirements() {
        return isset($this->data['requirements']) ?
            new Requirements($this->data['requirements']) :
            new Requirements();
    }

    /**
     * @return Pattern|null
     */
    public function getPattern() {
        if (isset($this->data['pattern'])) {
            $pattern = $this->data['pattern'];
            return new Pattern(is_array($pattern) ? $pattern['value'] : $pattern);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getDefaults() {
        if (isset($this->data['defaults'])) {
            $defaults = $this->data['defaults'];
            if (!$defaults instanceof \Closure && preg_match('/^([a-zA-Z\\\\])+:[a-z]([a-zA-Z])+$/', $defaults)) {
                return array_combine(['class', 'action'], explode(':', $defaults));
            }
            if ($defaults instanceof \Closure) {
                return array('callback' => $defaults);
            }
        }

        return array();
    }

    /**
     * @param $key
     * @param array $data
     * @return mixed|string
     */
    protected function get($key, array $data) {
        return isset($data[$key]) ? $data[$key] : null;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * Build route
     */
    public function build() {
        if (!empty($this->data)) {
            $name = $this->data['name'];
            if (isset($name) && !empty($name)) {
                $this->route = new Route($this->data['name'], $this->getPattern(),
                    $this->getDefaults(), $this->getRequirements());
                return true;
            }
        }
        return false;
    }

    /**
     * @return Route built route
     * @throws BuildException
     */
    public function getRoute() {
        if ($this->build()) {
            return $this->route;
        }
        throw new BuildException("Error when creating object, check whether the data used by the builder are fully charged");
    }
}