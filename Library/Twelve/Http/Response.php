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
class Response
{
    public
        $request;
    protected
        $_session;

    public function __construct(Request $request = null)
    {
        if(isset($request))
            $this->request = $request;
    }
    public function header($headerKey, $headerReplace = null)
    {
        header($headerKey, $headerReplace);
    }
    public function cookie($name, $value, $expires = 0, $domain = null, $path = null, $isSecure = false, $httponly = false)
    {
        if(headers_sent() && (bool) $this->outputBuffering === false || strtolower($this->outputBuffering) == 'off')

            return false;
        if($null === $expires)
            $expire = time() + (3600 * 24 * 30);
        if(substr($domain, 0, 1) != '.')
            $domain = '.' . $domain;

        $port = strpos($domain, ':');
        if($port != false) $domain =
            substr($domain, 0, $port);
        header(
            'Set-Cookie: ' . rawurlencode($name) , '=' . rawurlencode($value)
            . (empty($domain) ? '' : '; $Domain =' . $domain)
            . (empty($expires) ? '' : '; Max-$Age=' . $expires)
            . (empty($path) ? '' : '; Path=' . $path)
            . (!$isSecure ? '' : '; Secure')
            . (!$httponly ? '' : '; HttpOnly'), false);

        return true;
    }
    /**
    * Sets the headers to prevent caching for the different browsers
    *
    * Different browsers support different nocache headers, so several
    * headers must be sent so that all of them get the point that no
    * caching should occur
    *
    * @return  bool
    *
    * @access  public
    * @since   1.0.000
    * @static
    */
    public function noCache()
    {
        header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
        header("Pragma: no-cache");
        header('Cache-Control: no-store, no-cache');
    }
    public function httpCode($httpCode)
    {
        if (null !== $httpCode) {
            $this->_httpCode = $httpCode;
            $this->_protocol = $this->request->server('SERVER_PROTOCOL');
            header($this->_httpCode, $this->_protocol);
        }
    }
    public function redirect($url, $httpCode = 302)
    {
        $this->httpCode($httpCode);
        $this->header("Location: $url");
    }
    public function back()
    {
        $this->redirect($this->request->server('HTTP_REFERER'));
        $this->refresh();
    }
    public function refresh()
    {
        $this->redirect($this->request->server('REQUEST_URI' . '/'));
    }
}
