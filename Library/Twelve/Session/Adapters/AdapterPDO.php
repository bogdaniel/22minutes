<?php
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
namespace Twelve\Session\Adapters;
use \Twelve\Session\Interfaces\IAdapter;
use
    \Twelve\Http\Request,
    \Twelve\Session\Adapter,
    \PDO,
    \PDOException;

class AdapterPDO implements IAdapter
{
    protected
        $_lockTimeOut;
    public function __construct(Request $request, PDO $pdo, $options)
    {
        try {
            $this->pdo = $pdo;
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function read($sessionId)
    {
        $this->lockSession($sessionId, $this->_lockTimeOut);
        $this->sth = $this->pdo->prepare("SELECT `sessionValue` FROM `sessionData` WHERE `sessionId` = :sessionId AND `httpUserAgent` = :httpUserAgent AND sessionExpire > NOW() LIMIT 1");
        $this->sth->bindParam(':sessionId', $sessionId, PDO::PARAM_STR);
        $this->sth->bindParam(':httpUserAgent', $httpUserAgent, PDO::PARAM_STR);
        $this->sth->execute();
        if ($this->sth->rowCount()) {
            $this->result = $this->sth->fetch(PDO::FETCH_OBJ);

            return $this->result->sessionValue;
        }

        return '';
    }

    public function write($sessionId, $sessionValue)
    {
        // First Thing insert or update session's data:
        // first we will try to insert a new row in the database but if sessionId is alreayd in the database
        // let's update it
        try {
            $this->sth = $this->pdo->prepare
                        ("INSERT INTO `sessionData` (sessionId, httpUserAgent, sessionValue, sessionExpire)
                        VALUES
                        (:sessionId, :httpUserAgent, :sessionData, NOW() + INTERVAL 1 DAY)
                        ON DUPLICATE KEY UPDATE
                        sessionValue = :sessionData,
                        sessionExpire = NOW() + INTERVAL 7 DAY");
            $this->sth->bindParam(':sessionId', $sessionId, PDO::PARAM_STR);
            $this->sth->bindParam(':httpUserAgent', $httpUserAgent, PDO::PARAM_INT);
            $this->sth->bindParam(':sessionData', $sessionValue, PDO::PARAM_STR);
            $this->sth->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function destroy($sessionId)
    {
        $this->sth = $this->pdo->prepare("DELETE FROM sessionData WHERE sessionId = :sessionId");
        $this->sth->bindParam(':sessionId', $sessionId);
        $this->sth->execute();
    }
    public function lockSession($sessionId, $lockTimeOut)
    {
        try {
            $this->sth = $this->pdo->prepare("SELECT GET_LOCK(:sessionId, :lockTimeOut)");
            $this->sth->bindParam(':sessionId', $sessionId, PDO::PARAM_STR);
            $this->sth->bindParam(':lockTimeOut', $lockTimeOut, PDO::PARAM_INT);
            $this->sth->execute();
        } catch (PDOException $e) {
            echo $e->getMessage() . $this->sth->errorInfo();
        }
    }
    public function releaseLock($sessionId)
    {
        try {
            $this->sth = $this->pdo->prepare("SELECT RELEASE_LOCK(:sessionLock)");
            $this->sth->bindParam(':sessionLock', $sessionId, PDO::PARAM_STR);
            $this->sth->execute();
        } catch (PDOException $e) {
            echo $e->getMessage() . $this->sth->errorInfo();
        }
    }
    public function getActiveSessions()
    {
        $this->sth = $this->pdo->query("SELECT COUNT(sessionId) as count FROM `sessionData`");
        $result = $this->sth->fetch(PDO::FETCH_OBJ);

        return $result->count;
    }
    public function garbageCollector()
    {
        $this->pdo->exec("DELETE FROM sessionData WHERE sessionExpire <= NOW()");
    }
    public function delete($sessionId) {}
    public function clean($maxLifeTime) {}
}
