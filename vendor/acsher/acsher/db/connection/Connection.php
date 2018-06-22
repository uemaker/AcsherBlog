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

    }

    protected function getOptions() {

    }
}