<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\db\connection;


abstract class Connection
{
    protected $pdo;

    protected $options = [];

    protected $database;

    protected $tablePrefix = '';

    protected $config = [];

    public function __construct(array $config) {
        $this->config = $config;
        $this->init();
    }

    protected function init(){
        $this->pdo = $this->createPdo();
    }

    protected function createPdo(){
        list($username, $password) = [
            empty($config['username']) ? null : $config['username'],
            empty($config['password']) ? null : $config['password']
        ];
        $dsn = $this->getDsn();
        $options = $this->getOptions();
        try {
            $this->pdo = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
        }
    }

    protected function getDsn() {
        $config = $this->config;
        if(!empty($config['unix_socket'])){
            $dsn = "mysql:unix_socket={$config['unix_socket']};dbname={$config['database']}";
        }else {
            $port = !empty($config['port']) ? 'port='.$config['port'].';' : '';
            $dsn = "mysql:host={$config['host']};{$port}dbname={$config['database']}";
        }
        return $dsn;
    }

    protected function getOptions() {
        $config = $this->config;
        $options = isset($config['options']) ? $config['options'] : [];
        return array_diff_key($this->options, $options) + $options;
    }
}