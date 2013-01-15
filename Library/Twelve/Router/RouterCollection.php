<?php
namespace Twelve\Router;

use \Twelve\Utils;

class RouterCollection
{
	public $routeCollection = [];

    public function add($name, $pattern, $controller, $action, array $params = [])
    {
        if(!isset($this->routeCollection[$name]))
            $this->routeCollection[$name] =
        [
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action,
            'params' => $params,
        ];
    }
    public function findMatch($url)
    {
        foreach($this->routeCollection as $routeMap)
        {
            $this->regex = $this->buildRegex($routeMap['pattern'], $routeMap['params']);
            // Let's test the route.
            if(preg_match($this->regex, $url))
            {
                return ['controller' => $routeMap['controller'], 'action' => $routeMap['action']];
            }
            else
            {
                return ['controller' => $this->routeCollection['404']['controller'], 'action' => $this->routeCollection['404']['action']];
            }
        }
    }
    public function buildRegex($uri, array $params)
    {
        // Find {params} in URI
        if(preg_match_all('/\{(?:[^\}]+)\}/', $uri, $this->matches, PREG_SET_ORDER))
        {
            foreach($this->matches as $isMatch)
            {
                // Swap {param} with a placeholder
                $this->uri = str_replace($isMatch, "%s", $uri);
            }
            // Build final Regex
            $this->finalRegex = '/^' . preg_quote($this->uri, '/') . '$/';
            $this->finalRegex = vsprintf($this->finalRegex, $params);

            return $this->finalRegex;
        }
    }
    public function getCollection()
    {
        return $this->routeCollection;
    }
}