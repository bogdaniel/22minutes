<?php
namespace Twelve\Session\Interfaces;

interface IAdapter
{
    public function read($sessionId);
    public function write($sessionId, $sessionValue);
    public function delete($sessionId);
    public function clean($maxLifeTime);
}
