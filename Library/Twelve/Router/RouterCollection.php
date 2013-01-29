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
class RouterCollection
{
    public $routeCollection = [];
    protected $matches = [];

    public function add($name, $pattern, $controller, $action = null, array $params = [])
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
        foreach ($this->routeCollection as $routeMap) {
            $this->regex = $this->buildRegex($routeMap['pattern'], $routeMap['params']);
            // Let's test the route.
            if (preg_match($this->regex, $url)) {
                return ['controller' => $routeMap['controller'], 'action' => $routeMap['action']];
            }
        }

        return ['controller' => $this->routeCollection['404']['controller'], 'action' => $this->routeCollection['404']['action']];
    }
    public function buildRegex($uri, array $params)
    {
        // Find {params} in URI
        if (preg_match_all('/\{(?:[^\}]+)\}/', $uri, $this->matches, PREG_SET_ORDER)) {
            foreach ($this->matches as $isMatch) {
                // Swap {param} with a placeholder
                $this->uri = str_replace($isMatch, "%s", $uri);
            }
            // Build final Regex
            $this->finalRegex = '/^' . preg_quote($this->uri, '/') . '$/';
            $this->finalRegex = vsprintf($this->finalRegex, $params);
        } else {
            $this->finalRegex = '/^(' . preg_quote($uri, '/') . ')$/';
            $this->finalRegex = str_replace(array('\.', '\-'), array('.', '-'), $this->finalRegex);
        }

        return $this->finalRegex;
    }
    public function getCollection()
    {
        return $this->routeCollection;
    }
}
