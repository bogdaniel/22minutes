<?php
namespace Twelve\Session;
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
 * @subpackage  [ Session ]
 * @author      [ Bogdan Olteanu ] <[ olteanu.bogdan@gmail.com ], [Adam Boxall]>
 * @copyright   2012 [Bogdan Olteanu].
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://[ TwelveMinutes]
 * @version     @@1.0@@
 */
use \SessionHandlerInterface,
    \Twelve\Session\Interfaces\IAdapter,
    \Twelve\Session\Exception;

class Session implements SessionHandlerInterface
{
    public
        $options = [];
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function setAdapter(IAdapter $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function open($save_path, $session_name)
    {
        // Dummy function - open is handled upon construction of adapter.
    }

    public function close()
    {
        // Dummy function - close is handled upon destruction of adatper.
    }

    public function read($sessionId)
    {
        return $this->adapter->read($sessionId);
    }

    public function write($sessionId, $sessionValue)
    {
        return $this->adapter->write($sessionId, $sessionValue);
    }

    public function destroy($sessionId)
    {
        return $this->adapter->delete($sessionId);
    }

    public function gc($maxLifeTime)
    {
        return $this->adapter->clean($maxLifeTime);
    }
}
