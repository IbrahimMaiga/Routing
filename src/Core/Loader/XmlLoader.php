<?php


namespace Routing\Core;

/**
 * Class XmlLoader
 * @package Routing\Core\Loader
 *
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>
 */
class XmlLoader implements Loader {

    /**
     * @var string
     */
    private $path;
    /**
     * @var \DOMDocument
     */
    private $dom;
    /**
     * @var \DOMElement
     */
    private $root;
    /**
     * @var \DOMNodeList
     */
    private $routeNodes;

    /**
     * XmlLoader constructor.
     * @param $path
     */
    public function __construct($path) {
        $this->path = $path;
        $this->dom = new \DOMDocument();
        $this->dom->load($path);
        $this->root = $this->dom->documentElement;
        $this->routeNodes = $this->root->getElementsByTagName('route');
    }

    /**
     * Method used to load a configuration identified in a file.
     * @return array
     */
    public function load() {
        $routes = array();

        foreach ($this->routeNodes as $routeNode){
            $name = $routeNode->getAttribute('name');
            $data = array();
            $data['pattern'] = $this->getPatternValue($routeNode);
            $data['defaults'] = $this->getDefaultsValue($routeNode);
            $data['requirements'] = $this->getRequirementsValue($routeNode);
            $routes[$name] = $data;
        }
        return $routes;
    }

    /**
     * @param $routeNode
     * @return array
     */
    private function getPatternValue($routeNode){
        return $this->get($routeNode, 'pattern');
    }

    /**
     * @param $routeNode
     * @param $key
     * @return array
     */
    private function get($routeNode, $key)
    {
        $data = [];

        $elementRoot = $this->getFirst($routeNode, $key);
        $elements =  $elementRoot->attributes;
        foreach ($elements as $element) {
            $data = array_merge($data, [$element->nodeName => $element->nodeValue]);
        }

        return $data;
    }

    /**
     * @param $routeNode
     * @return mixed
     */
    private function getDefaultsValue($routeNode){
        $defaults = $this->getFirst($routeNode, 'defaults');
        return $defaults->nodeValue;
    }

    /**
     * @param $routeNode
     * @return array
     */
    private function getRequirementsValue(\DOMElement $routeNode) {
        $data = [];

        $requirements_root = $this->getFirst($routeNode, 'requirements');
        $requirements = $requirements_root->getElementsByTagName('requirement');
        foreach ($requirements as $requirement) {
            $data = array_merge($data, [$requirement->getAttribute('name') => $requirement->nodeValue]);
        }

        return $data;
    }

    /**
     * @param $routeNode
     * @param $key
     * @return mixed
     */
    private function getFirst(\DOMElement $routeNode, $key) {
        return $routeNode->getElementsByTagName($key)->item(0);
    }
}