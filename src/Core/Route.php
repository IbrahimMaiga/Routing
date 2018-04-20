<?php

namespace Routing\Core;

/**
 * Class Route
 * @package Routing\Core
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class Route implements \Serializable {

    /**
     * @var string
     */
    private $name;

    /**
     * @var Pattern
     */
    private $pattern;
    /**
     * @var array
     */
    private $defaults;

    /**
     * @var Requirements
     */
    private $requirements;

    /**
     * Route constructor.
     * @param $name string route name
     * @param Pattern $pattern
     * @param array $defaults
     * @param Requirements $requirements
     * @internal param Action $action
     */
    public function __construct($name, Pattern $pattern, array $defaults, Requirements $requirements = null){
        $this->name = $name;
        $this->pattern = $pattern;
        $this->defaults = $defaults;
        $this->requirements = $requirements ?:  new Requirements();
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Pattern
     */
    public function getPattern(){
        return $this->pattern;
    }

    public function setPattern(Pattern $patten) {
        $this->pattern = $patten;

        return $this;
    }

    public function getDefaults() {
        return $this->defaults;
    }

    public function setDefaults(array $defaults) {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @return Requirements
     */
    public function getRequirements(){
        return $this->requirements;
    }

    public function setRequirements(Requirements $requirements){
        $this->requirements = $requirements;

        return $this;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            'name' => $this->name,
            'pattern' => $this->pattern,
            'defaults' => $this->defaults,
            'requirements' => $this->requirements
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->name = $data['name'];
        $this->pattern = $data['pattern'];
        $this->defaults = $data['defaults'];
        $this->requirements = $data['requirement'];
    }
}
