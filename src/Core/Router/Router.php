<?php

namespace Routing\Core;

use Routing\Configuration\ConfigurationInterface;
use Routing\Exception\RequirementsException;
use Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Router
 * @package Routing\Core\Router
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class Router implements RouterInterface, UrlGeneratorInterface
{
    use RegexFormat;
    /**
     * @var bool|ConfigurationInterface
     */
    private $configuration;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * the current match route
     * @var Route
     */
    private $current;

    /**
     * @var Request
     */
    private $request;

    /**
     * Router constructor.
     * @param $obj ConfigurationInterface|ConfigBuilder
     * @param Matchable $matcher
     * @param Request|null $request
     */
    public function __construct($obj, Matchable $matcher, Request $request = null)
    {
        if (!($obj instanceof ConfigurationInterface) and !($obj instanceof ConfigBuilderInterface)) {
            $message = "The parameter %s must be type Routing\\Configuration\\ConfigurationInterface or Routing\\\Builder\\ConfigBuilderInterface";
            throw new \InvalidArgumentException(sprintf($message, $obj));
        }

        if ($obj instanceof ConfigurationInterface) {
            $this->configuration = $obj;
        }

        if ($obj instanceof ConfigBuilderInterface) {
            $this->configuration = $obj->getConfiguration();
        }

        $this->request = $request ?: Request::createFromGlobals();
        $this->matcher = $matcher;
        $this->routes = $this->configuration->getRoutes();
    }

    /**
     * Interpreter and checked if the URL matches pattern of a defined route
     * @return array if route pattern match url
     * @throws RouteNotFoundException if route not found
     */
    public function interpret()
    {
        foreach ($this->routes as $route) {
            if ($this->matcher->match($route, $this->request)) {
                $this->current = $route;
                return $this->matcher->getMatches();
            }
        }
        throw new RouteNotFoundException(sprintf('No routes matches this path %s', $this->request->getPathInfo()));
    }

    /**
     * Returns current route
     * @return Route
     */
    public function getCurrentRoute()
    {
        return $this->current;
    }

    /**
     * @return array all routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Returns generated url for parameter <b>name</b>
     * @param string $name name of route to generate
     * @param array $params
     * @param int $type type of generation
     * <ul>
     * <li>
     * <b>RELATIVE_URL</b> - generate url without <i>scheme</i>,
     * <i>host</i> and <i>port</i>
     * </li>
     * <li>
     * <b>ABSOLUTE_URL</b> - generate url with <i>scheme</i>,
     * <i>host</i> and <i>port</i>
     * </li>
     * </ul>
     * @return string $url generated url
     */
    public function generate($name, array $params = [], $type = self::RELATIVE_URL)
    {
        $route = $this->getRoute($name);
        if ($route === null) {
            throw new RouteNotFoundException(sprintf('Route %s not found, enable to generate this', $name));
        }

        $requirement = $this->getRequirements($route);
        $this->checkParams($params, $requirement);

        $pattern = $this->getPattern($route);
        $url = preg_replace_callback(Pattern::R_PATTERN,
            function ($match) use ($params) {
                return $params[$match[1]];
            },
            $pattern->getValue()
        );

        if ($type === self::ABSOLUTE_URL) {
            $partial = $requirement->get('scheme') . "://{$this->request->getHost()}";
            if ($this->request->getPort() != null) {
                $partial .= ":{$this->request->getPort()}";
            }
            $url = $partial . $url;
        }

        return $url;
    }

    /**
     * Returns sets of routes satisfactory on condition, empty array otherwise
     * @param callable $callable function that apply condition
     * @return array the sets of filtered list
     */
    public function filterRoutes(callable $callable)
    {
        return array_filter($this->routes, $callable);
    }

    /**
     * Returns $route is route found, null otherwise
     * @param string $name route name
     * @return Route|null
     */
    private function getRoute(string $name)
    {
        foreach ($this->routes as $route) {
            if ($route->getName() === $name) {
                return $route;
            }
        }
        return null;
    }

    /**
     * Returns route pattern
     * @param Route $route
     * @return Pattern the route pattern
     */
    private function getPattern(Route $route)
    {
        return $route->getPattern();
    }

    /**
     * Returns route requirements
     * @param Route $route
     * @return Requirements
     */
    private function getRequirements(Route $route)
    {
        return $route->getRequirements();
    }

    /**
     * Check if the parameters in the params variables match those expected
     * @param array $params array of parameters
     * @param Requirements $requirement the route requirements
     * @throws RequirementsException if the requirements is incorrect for one of the params keys
     */
    private function checkParams(array &$params, Requirements $requirement)
    {
        if (!empty($params)) {
            $urlSpecifiesKeys = $requirement->getUrlSpecifiesKeys();
            if (array_keys($params) !== $urlSpecifiesKeys) {
                $params = array_combine($urlSpecifiesKeys, array_values($params));
            }
            $paramsKeys = array_keys($params);
            foreach ($paramsKeys as $paramsKey) {
                if (!preg_match($this->formatRegex($requirement->get($paramsKey)), $params[$paramsKey])) {
                    throw new RequirementsException(sprintf('Incorrect requirement for key : %s', $paramsKey));
                }
            }
        }
    }

    public function createRouterFromStd(string $file)
    {
        return new self(ConfigBuilder::createBuilderFromStd($file), Matcher::create());
    }
}