<?php
namespace Twelve\Router;

use Twelve\Utils\Utils;

class Router
{
    public
        $controller;

    public function dispatch(array $route = [], $url)
    {
        $this->setController($route['controller']);
        $this->setAction($route['action']);
        $this->setParams(explode('/', $url));
        call_user_func_array([new $this->controller, $this->action], $this->params);
    }
    public function setController($controller)
    {
        $this->controller = preg_replace('/:/', '\\', $controller);
    }
    public function getController()
    {
        return $this->controller;
    }
    public function setAction($action)
    {
        $this->action = $action;
    }
    public function getAction()
    {
        return $this->action;
    }
    public function setParams($params)
    {
        $this->params = array_slice($params, 3);
    }
    public function getParams()
    {
        return $this->params;
    }
}