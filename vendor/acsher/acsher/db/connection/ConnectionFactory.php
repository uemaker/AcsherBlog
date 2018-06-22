<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\db\connection;


/**
 * ConnectionFactory class
 *
 * @author felybo
 * @since 1.0
 */
class ConnectionFactory
{

     public $config = [
            'driver' => 'mysql',
            'database' => '',
            'prefix' => ''
         ];

    public function create($config, $name){

    }

    protected function createConnection($driver) {

    }
}