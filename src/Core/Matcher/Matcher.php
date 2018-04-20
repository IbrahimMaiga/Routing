<?php

namespace Routing\Core;

use Routing\Exception\RequirementsException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Matcher
 * @package Routing\Core\Matcher
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class Matcher implements Matchable
{
    use RegexFormat, Utility;

    /**
     * @var array
     */
    private $matches;

    /**
     * @var array
     */
    private $params = array();

    /**
     * Matcher constructor.
     */
    public function __construct() {
    }

    /**
     * Returns true if route match otherwise false
     * @param Route $route
     * @param Request $request
     * @return bool true if match otherwise false
     * @throws \Exception
     */
    public function match(Route $route, Request $request)
    {
        $url = trim($request->getPathInfo(), '/');
        $value = $this->replace(trim($route->getPattern()->getValue(), '/'));

        if (!preg_match($this->formatRegex($value), $url, $params)) {
            return false;
        }

        $this->params = $params;
        array_shift($this->params);

        $this->checkRequirements($route->getRequirements()->getSpecifies(), $this->params);

        $this->checkSchemes($request, $route->getRequirements()->get('scheme'));
        $this->checkMethod($request, $route->getRequirements()->get('method'));
        $this->matches = array_merge($route->getDefaults(), ['params' => $this->params]);

        return $this->matches != null;
    }

    /**
     * @param $specifies
     * @param $values
     * @throws RequirementsException
     */
    private function checkRequirements($specifies, &$values)
    {
        $tmp = $specifies;
        $tmp = $this->unsetSomeValuesFrom($tmp, 'method', 'scheme');
        $names = array_keys($tmp);
        if (count($names) !== count($values)) {
            throw new RequirementsException("missing requirements in the list");
        }

        for ($i = 0; $i < count($names); $i++) {
            $regex = $tmp[$names[$i]];
            if (!preg_match($this->formatRegex($regex), $values[$i])) {
                throw new RequirementsException(sprintf(
                    "The key %s does not match regex %s",
                    $names[$i],
                    $values[$i]));
            } else {
                if ($regex === '\d+') {
                    $values[$i] = (int)$values[$i];
                }
            }
        }
    }

    /**
     * Returns matches params
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @param $pattern
     * @param $replacement
     * @param $value
     * @return mixed
     */
    private function replace($value, $pattern = Pattern::R_PATTERN, $replacement = Pattern::R_REPLACEMENT)
    {
        return preg_replace($pattern, $replacement, $value);
    }

    /**
     * @param Request $request
     * @param $method
     * @throws \Exception
     */
    private function checkMethod(Request $request, $method)
    {
        if (!$this->check($method, $request->getMethod())) {
            $message = 'this route %s is not accessible for method'
                . (count(explode('|', $method)) > 1 ? 's' : '') . ' %s';
            throw new \RuntimeException(sprintf($message, $request->getPathInfo(), str_replace('|', ', ', $request->getMethod())));
        }
    }

    private function checkSchemes(Request $request, $scheme)
    {
        if (!$this->check($scheme, $request->getScheme())) {
            $message = 'this route %s is not accessible in %s';
            throw new \RuntimeException(sprintf($message, $request->getPathInfo(), str_replace('|', ', ', $request->getScheme())));
        }
    }

    private function check($pattern, $subject)
    {
        return in_array(strtolower($subject), explode('|', $pattern));
    }

    /**
     * @param $tmp
     * @param array $keys
     * @return array $tmp
     */
    private function unsetSomeValuesFrom($tmp, ...$keys)
    {
        foreach ($keys as $key) unset($tmp[$key]);
        return $tmp;
    }

    public static function create()
    {
        return new self();
    }
}