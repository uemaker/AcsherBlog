<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\db;


/**
 * DbManager class
 *
 * @author felybo
 * @since 1.0
 */
class DbManager
{
    public $connections = [];

    public function __construct() {
    }

    protected function connection($name) {

    }

    public function disconnection($name) {

    }

    public function reconnection() {

    }

    public function createConnection() {

    }

    public function setDefaultConnection() {

    }

    public function getDefaultConnection() {

    }

    public function getConnections() {
        return $this->connections;
    }

    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}