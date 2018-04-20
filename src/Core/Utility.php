<?php

namespace Routing\Core;

/**
 * Trait Utility
 * @package Routing\Core
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
trait Utility
{
    /**
     * Returns route pattern in named form
     * @param Route $route
     * @return mixed
     */
    protected function named_form(Route $route) {
        return preg_replace_callback
        (
            Pattern::R_PATTERN,
            function ($mask) use ($route) {
                $surrounded = $this->surround($route->getRequirements()->get($mask[1]), '[', ']');
                return '?<' . htmlspecialchars($mask[1]) . '>(' . $surrounded . ')';
            },
            $route->getPattern()->getValue()
        );
    }

    /**
     * Format <code>$value</code> in specified form
     * @param $value
     * @param $begin
     * @param $end
     * @return string
     */
    private function surround($value, $begin, $end = '') {
        if (empty($end)) {
            $end = $begin;
        }
        if (false === strpos($value, $begin) && false === strpos(strrev($value), $end)) {
            $value = $begin . $value . $end;
        }

        return $value;
    }

    /**
     * @param $arg array
     * @param string $glue
     * @return array $formatted expression
     */
    protected function format($arg, $glue = ':') {
        $formatted = array();
        foreach ($arg as $key => $value) {
            $formatted[] = $key . $glue . $value;
        }
        return $formatted;
    }
}