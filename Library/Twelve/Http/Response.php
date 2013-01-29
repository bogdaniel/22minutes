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
    const OK 				= 200; // The Request has succeded.
    const CREATED 			= 201; // The Reuqest has been fulfielled and resulted in a new resource being created.
    const NO_CONTENT 		= 204; // The Server has fulfilled the reuqest but does not need to return an entity-body.
    const MOVED_PERM 		= 301;
    const FOUND 			= 302;
    const NOT_MODIFIED 		= 304;
    const TEMP_REDERICT 	= 307;
    const BAD_REQUEST 		= 400; // The Request could no be understood by the server due to malformed syntax.
    const UNAUTHORIZED 		= 401; // The Request requires user authentication.
    const FORBIDDEN 		= 403; // The Server understood the request, but is refusing to fulfill it.
    const NOT_FOUND 		= 404; // The Server has not found anything matching the Request-URI
    const NOT_ALLOWED 		= 405; // The Method specified in the Request-Line is not allowed for the resource identified by the Request-URI
    const NOT_ACCEPTABLE 	= 406; // The Server can only generate a response that is not accepted by the client.
    const REQUEST_TIMEOUT 	= 408;
    const SERVER_ERROR 		= 500;
    const NOT_IMPLEMENTED 	= 501; // The Server does not support the functionality required to fulfill the request.
    const UNAVAILABLE 		= 503;
    const TIMEOUT 			= 504;

    protected $statusCodes =
            [
                self::OK              => 'HTTP/1.1 200 OK',
                self::CREATED         => 'HTTP/1.1 201 Created',
                self::NO_CONTENT      => 'HTTP/1.1 204 No Content',
                self::MOVED_PERM      => 'HTTP/1.1 301 Moved Permanently',
                self::FOUND           => 'HTTP/1.1 302 Found',
                self::NOT_MODIFIED    => 'HTTP/1.1 304 Not Modified',
                self::TEMP_REDERICT   => 'HTTP/1.1 307 Temporary Redirect',
                self::BAD_REQUEST     => 'HTTP/1.1 400 Bad Request',
                self::UNAUTHORIZED    => 'HTTP/1.1 401 Unauthorized',
                self::FORBIDDEN       => 'HTTP/1.1 403 Forbidden',
                self::NOT_FOUND       => 'HTTP/1.1 404 Not Found',
                self::NOT_ALLOWED     => 'HTTP/1.1 405 Method Not Allowed',
                self::NOT_ACCEPTABLE  => 'HTTP/1.1 406 Not Acceptable',
                self::REQUEST_TIMEOUT => 'HTTP/1.1 408 Request Timeout',
                self::SERVER_ERROR    => 'HTTP/1.1 500 Internal Server Error',
                self::NOT_IMPLEMENTED => 'HTTP/1.1 501 Not Implemented',
                self::UNAVAILABLE     => 'HTTP/1.1 503 Service Unavailable',
                self::TIMEOUT         => 'HTTP/1.1 504 Gateway Timeout'
            ];
    public $headers = [];
    public $code = '200';
    public function addHeader($string)
    {
        $this->headers[] = $string;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
    public function sendHeaders()
    {
        $this->addHeader($this->statusCodes[$this->code]);
        $this->addHeader('Status: ' . $this->code);

        $this->headers = $this->getHeaders();
        for ($i = 0; $i < count($this->headers); $i++) {
            header($this->headers[$i]);
        }
    }
    /**
     * @param  int  $limit     Number of requests allowed
     * @param  int  $remaining Number of requests remaining
     * @return void
     */
    public function setRateLimitHeader($limit, $remaining)
    {
        $this->addHeader('X-RateLimit-Limit: ' . $limit);
        $this->addHeader('X-RateLimit-Remaining: ' . $remaining);
    }
    /**
     * @param  string $value
     * @return void
     */
    public function setEtagHeader($value)
    {
        $this->addHeader(sprintf('ETag: "%s"', $value));
    }
    public function setCacheHeader($seconds = null)
    {
        if(is_numeric($seconds))
            $this->addHeader('Cache-Control: max-age=' . $seconds . ', must-revalidate');
        else {
            $this->addHeader('Cache-Control: no-cache, must-revalidate');
            $this->addHeader('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        }
    }
    /**
     * @param  null|int $seconds 3600 seconds = 1 hour
     * @return void
     */
    public function setCacheHeader($seconds = null)
    {
        if (is_numeric($seconds)) {
            $this->addHeader('Cache-Control: max-age=' . $seconds . ', must-revalidate');
        } else {
            $this->addHeader('Cache-Control: no-cache, must-revalidate');
            $this->addHeader('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        }
    }
}
