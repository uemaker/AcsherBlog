<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

require __DIR__ . '/../vendor/autoload.php';


use acsher\db\query\Builder;

$builder = new Builder();

$builder = $builder->select('*')->from('user')
    ->join('user_info','user_info.id','=','user.id','left')
    ->where('age','>',10)
    ->addWhere('name','=','atao');

$binds = $builder->getBindings();

print_r($builder);
