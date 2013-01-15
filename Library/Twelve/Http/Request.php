<?php
namespace Twelve\Http;
use ArrayIterator;
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
 * @subpackage  [ Http ]
 * @author      [ Bogdan Olteanu ] <[ olteanu.bogdan@gmail.com ]>
 * @copyright   2012 [Bogdan Olteanu].
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://[ TwelveMinutes]
 * @version     @@1.0@@
 */
class Request
{
    public
        $session,
        $cookie,
        $post,
        $get,
        $request;
    protected
        $_defaultParam = 'SCRIPT_NAME',
        $_server;
    public function __construct($server, $cookie, $post, $get, $request)
    {
        $this->_server = $server;
        $this->cookie = new \ArrayIterator($cookie);
        $this->post   = new \ArrayIterator($post);
        $this->get    = new \ArrayIterator($get);
        $this->request = new \ArrayIterator($request);
    }
    /**
     * [$replaceHeader description]
     * Requests a $header  from the server
     * with optional $param replaceHader
     * @var [string]
     * @var [boolean]
     */
    public function header($headerKey, $replaceHeader = null)
    {
        $headerkey = 'HTTP_' . strtoupper(str_replace('-', '_', $headerKey));

        return isset($this->_server[$headerKey]) ? $this->_server[$headerKey] : $replaceHeader;
    }

    /**
     * [session description]
     * Checkes to see if a session key is set
     * if it's set then it will return it.
     * @$param  [string] $sessionKey     [description]
     * @$param  [string] $replaceSession [description]
     * @return [string]
     */
    public function session($sessionKey, $replaceSession = null)
    {
        return isset($this->session[$sessionKey]) ? $this->session[$sessionKey] : $replaceSession;
    }

    /**
     * [cookie description]
     * Requests Cookies From The Server Yummy
     * @$param  [string] $cookieKey     [description]
     * @$param  [string] $replaceCookie [description]
     * @return [boolean]
     */
    public function cookie($cookieKey, $replaceCookie = null)
    {
        return isset($this->cookie[$cookieKey]) ? $this->cookie[$cookieKey] : $replaceCookie;
    }

    /**
     * [$server description]
     * Get $_SERVER params :-)
     * @$param  [string] $param [description]
     * @return [type] [description]
     */
    public function server($param)
    {
        return isset($this->_server[$param]) ? $this->_server[$param] : $this->_defaultParam;
     }
    /**
     * [https description]
     * Is the request secure?
     * If $requiredHttpProtocol then redirect to the secure version of the URL
     * @param  boolean   $requiredHttpProtocol [description]
     * @return [boolean]
     */
    public function https($requiredHttpProtocol = false)
    {
        $this->secureProtocol = isset($this->_server['HTTPS']) && $this->_server['HTTPS'];
        if (!$this->secureProtocol && $requiredHttpProtocol) {
            $this->_url = 'https://' . $this->_server['HTTP_HOST'] . $this->_server['REQUEST_URI'];
            $this->header('Location:' . $this->_url);
        }

        return $this->secureProtocol;
    }

//TODO
//Remove Method
    /**
     * [$method description]
     * Gets the $request $method, or checks it against $requestMethod
     * - e.g. $method('post') => true
     * @$param  [string] $requestMethod [description]
     * @return [string]
     */
    public function method($requestMethod = null)
    {
        $this->requestMethod = isset($this->_server['REQUEST_METHOD']) ? $this->_server['REQUEST_METHOD'] : 'GET';
        if(null != $requestMethod)

            return strcasecmp($this->requestMethod, $requestMethod) === 0;

        return $this->requestMethod;
    }
//TODO

    /**
    * Returns the IP address of the client
    *
    * @param   bool  $trustProxyHeaders  Whether or not to trust the
    *                                      proxy headers HTTP_CLIENT_IP
    *                                      and HTTP_X_FORWARDED_FOR. ONLY
    *                                      use if your server is behind a
    *                                      proxy that sets these values
    * @return  string
    *
    * @access  public
    * @since   1.0.000
    * @static
    */
    public function ip($trustProxyHeaders = false)
    {
        if(!$trustProxyHeaders)

            return $this->server['REMOTE_ADDR'];
        if(!empty($this->server['HTTP_CLIENT_IP']))
            $this->ip = $this->server['HTTP_CLIENT_IP'];
        if(!empty($this->server['HTTP_X_FORWARDED_FOR']))
            $this->ip = $this->server['HTTP_X_FORWARDED_FOR'];
        else
            $this->ip = $this->server['REMOTE_ADDR'];

        return $this->ip;
    }

    /**
     * [userAgent description]
     * Return the User Agent
     * @return [string]
     */
    public function userAgent()
    {
        return isset($this->_server['HTTP_USER_AGENT']) ? $this->_server['HTTP_USER_AGENT'] : null;
    }

    /**
     * [uri description]
     * Returns The Current URI
     * @return [string]
     */
    public function uri()
    {
        if(isset($this->_server['REQUEST_URI']))
            return $this->_server['REQUEST_URI'];
    }

    /**
     * [__isset description]
     * Checks to see if a params has been set
     * @$param  [string]  $param [description]
     * @return boolean
     */
    public function __isset($param)
    {
        return isset($this->request[$param]);
    }

    /**
     * [__get description]
     * If set will return a $param from $_REQUEST
     * @$param  [string] $param [description]
     * @return [type]
     */
    public function __get($param)
    {
        return isset($this->request[$param]) ? $this->request[$param] : null;
    }

    /**
     * [__set description]
     * Tries to set a $param in the _REQUEST
     * @$param [string] $param [description]
     * @$param [string] $value [description]
     */
    public function __set($param, $value)
    {
        $this->request[$param] = $value;
    }

    /**
     * [__unset description]
     * If a $param exsits in the  _REQEST array will try to unset if
     * @$param [string] $param [description]
     */
    public function __unset($param)
    {
        if(isset($this->request[$param]))
            unset($REQUEST[$param]);
        else
            throw new Exception("Param $param not Set");
    }
}
