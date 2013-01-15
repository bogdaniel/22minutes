<?php
namespace Twelve\Router;
use \Twelve\Utils\Utils;
class Router
{
    protected
        $_siteName,
        $_uri,
        $_unsecureUri,
        $_requestMethod,
        $_route = [],
        $_uriSegments = [];

    public function __construct($siteName, $url = null)
    {
        $this->_siteName = $siteName;
        if($url === null)
                $this->_unsecureUri = $this->secureUri($_SERVER['REQUEST_URI']);
        if($requestMethod = null)
            $this->_requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        $this->dispatchRoute();

    }

    public function Map($requestMethod, $routeController, $routeAction, $routeName = null, $routePriority = '9999')
    {
        if(!isset($this->_route[$routeName]))
            $this->_route[$routeName] = [
            'requestMethod' => $requestMethod,
            'routeController' => $routeController,
            'routeAction' => $routeAction,
            'routeName' => $routeName,
            'routePriority' => $routePriority];
        else
            echo "Sorry $routeName already exists";
        ksort($this->_route);
    }

    public function setDefaultRoute($callBack)
    {
        $this->_defaultRoute = $callBack;
    }

    public function getRoutes()
    {
        Utils::var_dump($this->_route);
    }

    public function dispatchRoute()
    {
        $this->_uriSegments = array_filter(explode('/', $this->_uri));
        $controller = '\\' . $this->_siteName . '\Controller\\' . $this->_uriSegments[1];
        unset($this->_uriSegments[1]);
        $action = $this->_uriSegments[2];
        unset($this->_uriSegments[2]);
        $controller = new $controller;
        if(!empty($this->_uriSegments))
            call_user_func_array([$controller, $action], $this->_uriSegments);
        else
            call_user_func([$controller, $action]);
    }

    public function secureUri($url)
    {
        // Requested URL might contain MVC/Index.php, this will remove the MVC part from the  URI
        $this->_uri = str_replace(dirname($_SERVER['SCRIPT_NAME'] ), '', $url);
        // Let's Remove the Query String if there is One
        $this->_queryString = strpos($url, '?');
        if($this->_queryString !== false)
            $this->_uri = substr($this->_uri, 0, $this->_queryString);
        // If the Uri looks like http://localhost/index.php/path/to/folder remove  the index.php
        if(substr($this->_uri, 1, strlen(basename($_SERVER['SCRIPT_NAME']))) == basename($_SERVER['SCRIPT_NAME']))
            $this->_uri = substr($this->_uri, strlen(basename($_SERVER["SCRIPT_NAME"])) + 1);
        // Let's end the Uri with /
        $this->_uri = rtrim($this->_uri, '/') . '/';
        // Replace Multiple Slashes
        $this->_uri = preg_replace('/\/+/', '/', $this->_uri);

        // Let's Return a Favor
        return $this->_uri;
    }
}
