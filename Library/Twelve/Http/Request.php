<?php
namespace Twelve\Http;
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
    public $post;
    public $request;
    public $get;
    public $cookie;
    public $server;

    public function __construct($post = null, $request = null, $get = null, $cookie = null, $server = null)
    {
        if(empty($post))
            $this->post = $_POST;
        else
            $this->post = $post;
        if(empty($request))
            $this->request = $_REQUEST;
        else
            $this->request = $request;
        if(empty($get))
            $this->get = $_GET;
        else
            $this->get = $get;
        if(empty($cookie))
            $this->cookie = $_COOKIE;
        else
            $this->cookie = $cookie;
        if(empty($server))
            $this->server = $_SERVER;
        else
            $this->server = $server;
    }
    /**
     * [$server description]
     * Get $server params :-)
     * @$param  [string] $param [description]
     * @return [type] [description]
     */
    public function server($param)
    {
        if(!empty($this->server[$param]))

            return $this->server[$param];
        else
            return false;
    }
    public function post($param)
    {
        if(!empty($this->post[$param]))

            return $this->post[$param];
        else
            return false;
    }
    public function request($param)
    {
        if(!empty($this->request[$param]))

            return $this->request[$param];
        else
            return false;
    }
    public function get($param)
    {
        if(!empty($this->get[$param]))

            return $this->get[$param];
        else
            return false;
    }
    public function cookie($param)
    {
        if(!empty($this->cookie[$param]))

            return $this->cookie[$param];
        else
            return false;
    }
    public function userAgent()
    {
        if(!$this->server('HTTP_USER_AGENT'))

            return false;
        else
            return $this->server('HTTP_USER_AGENT');
    }
    /**
     * [uri description]
     * Returns The Current URI
     * @return [string]
     */
    public function uri()
    {
        if(!$this->server('REQUEST_URI'))

            return false;
        else
            return $this->server('REQUEST_URI');
    }
    public function baseUrl()
    {
        return sprintf('%s://%s', $this->server('HTTPS') ? 'https' : 'http', $this->server('SERVER_NAME'));
    }
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

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
    public function getIp($trustProxyHeaders = false)
    {
        if(!$trustProxyHeaders)

            return $this->server("REMMOTE_ADDR");
        if(!$this->server('HTTP_CLIENT_IP'))
            $this->ip = $this->server('HTTP_CLIENT_IP');
        if(!$this->server('HTTP_X_FORWARDED_FOR'))
            $this->ip = $this->server('HTTP_X_FORWARDED_FOR');
        else
            $this->ip = $this->server('REMOTE_ADDR');

        return $this->ip;
    }
}
