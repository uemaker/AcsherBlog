<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\db\query;

use acsher\base\Mixable;
use acsher\exception\InvalidParamException;
use acsher\exception\PropertyNotFoundException;
use acsher\util\Arr;


/**
 * JoinBuilder class
 *
 * @author felybo
 * @since 1.0
 */
class JoinBuilder extends Builder
{

    public $parentBuilder;

    public $joinType;

    public $joinTable;

    public function __construct(Builder $builder, $type, $table)
    {
        $this->parentBuilder = $builder;
        $this->joinType = $type;
        $this->joinTable = $table;

        parent::__construct();
    }

    public function on($column, $operator = null, $value = null, $boolean = 'and', $char=null) {

        $this->whereColumn($column, $operator, $value, $boolean, $char);
    }

    public function newQuery()
    {
        return new static($this->parentBuilder, $this->joinType, $this->joinTable);
    }
}