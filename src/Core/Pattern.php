<?php

namespace Routing\Core;

/**
 * Class Pattern
 * @package Routing\Core
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class Pattern
{
    const R_PATTERN = "/{([\w-_]+)}|:([\w-_]+)/";
    const R_REPLACEMENT = '([^/]+)';

    /**
     * @var string
     */
    private $value;

    /**
     * Pattern constructor.
     * @param string $value pattern value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get pattern value
     * @return string pattern value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Sets pattern value
     * @param $value pattern value
     * @return $this current instance
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }
}