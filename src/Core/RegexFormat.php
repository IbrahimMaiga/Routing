<?php

namespace Routing\Core;

/**
 * Trait RegexFormat
 * @package Routing\Core
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
trait RegexFormat
{
    /**
     * Returns regex representation of string passed in params
     * @param $regex string the regular expression
     * @param string $flag expression flag
     * @return string the formatted regular expression
     */
    private function formatRegex($regex, $flag = 'i') {
        return "#^$regex$#$flag";
    }
}