<?php
namespace Twelve\Router;
/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2012 [Bogdan Olteanu] <[olteanu.bogdan@gmail.com]>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package     [Twelve]
 * @subpackage  [ Router ]
 * @author      [ Bogdan Olteanu ] <[ olteanu.bogdan@gmail.com ]>
 * @copyright   2012 [Bogdan Olteanu].
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://[ TwelveMinutes]
 * @version     @@1.0@@
 */
class Router
{
    public $controller;
    public $action;

    public function dispatch(array $route = [], $url = null)
    {
        $this->setController($route['controller']);
        $this->setAction($route['action']);
        $this->setParams(explode('/', $url));
        if(!empty($this->params))
            call_user_func_array([new $this->controller, $this->action], $this->params);
        else
            call_user_func([new $this->controller, $this->action]);
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
