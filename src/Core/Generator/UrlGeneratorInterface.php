<?php

namespace Routing\Core;

/**
 * Interface UrlGeneratorInterface
 * @package Routing\Core
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
interface UrlGeneratorInterface
{
    const RELATIVE_URL = 1;
    const ABSOLUTE_URL = 2;

    /**
     * Returns generated url
     * @param $key
     * @param array $params
     * @param int $type
     * @return string generated url
     */
    public function generate($key, array $params = [], $type = self::RELATIVE_URL);
}