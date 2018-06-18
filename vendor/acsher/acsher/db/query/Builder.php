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
use Closure;


/**
 * Builder class
 *
 * @author felybo
 * @since 1.0
 */
class Builder
{
    use Mixable {
        __call as mixableCall;
    }

    public $connection;

    public $columns;

    public $bindings = [
        'select' => [],
        'from'   => [],
        'join'   => [],
        'where'  => [],
        'having' => [],
        'order'  => [],
        'union'  => [],
    ];

    public $operations = [
        '=',
        '<',
        '>',
        '<=',
        '>=',
        '<>',
        '!=',
        '<=>',
        'between',
        'not between',
        'in',
        'not in',
        'like',
        'not like',
        'regexp',
        'not regexp',
    ];

    public $from;

    public $joins;

    public $wheres;

    public $groups;

    public $havings;

    public $orders;

    public $limit;

    public $offset;

    public $unions;

    public $unionLimit;

    public $unionOffset;

    public $unionOrders;

    public function __construct() {

    }

    public function getConnection() {
        return $this->connection;
    }

    public function select($columns = ['*']) {
        $this->columns = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    public function addSelect($column) {
        $column = is_array($column) ? $column : func_get_args();
        $this->columns = array_merge((array) $this->columns, $column);

        return $this;
    }

    public function from($table) {
        $this->from = $table;

        return $this;
    }

    public function join($table, $column, $operator = null, $value = null, $type = 'inner', $where = false) {
        $join = new JoinBuilder($this, $type, $table);

        $method = $where ? 'where' : 'on';
        $this->joins[] = $join->$method($column, $operator, $value);
        print_r($join);exit;

        $this->addBinding($join->getBindings(), 'join');

        return $this;
    }

    public function leftJoin() {

    }

    public function rightJoin() {

    }

    public function addJoin() {

    }

    public function where($column, $operator = null, $value = null, $char = null) {
        $this->whereColumn($column, $operator, $value, '', $char);

        return $this;
    }

    public function andWhere($column, $operator = null, $value = null, $char = null) {
        $this->whereColumn($column, $operator, $value, 'and', $char);

        return $this;
    }

    public function orWhere($column, $operator = null, $value = null, $char = null) {
        $this->whereColumn($column, $operator, $value, 'or', $char);

        return $this;
    }

    public function addWhere($column, $operator = null, $value = null, $boolean = 'and', $char=null) {
        $this->whereColumn($column, $operator, $value, $boolean, $char);

        return $this;
    }

    public function whereColumn($column, $operator = null, $value = null, $boolean = 'and', $char=null) {
        if(is_array($column)){
            foreach ($column as $key => $val) {
                if(is_numeric($key) && is_array($val)) {
                    $this->where(...array_values($val));
                } else {
                    $this->where($key, '=', $val, $boolean);
                }
            }
            return;
        }
        if(!$this->checkOperation($operator)) {
            $value = $operator;
            $operator = '=';
        }
        if(!$this->checkChar($char)) {
            throw new InvalidParamException(sprintf(
               "Invalid param char: %s",
               $char
            ));
        }
        $this->wheres[] = compact('column','operator', 'value', 'boolean', 'char');

        return;
    }

    public function between($column, array $value, $not = false, $boolean = 'and', $char=null) {
        $operator = $not ? 'not between' : 'between';
        $this->whereColumn($column, $operator, $value, $boolean, $char);

        return $this;
    }

    public function notBetween($column, array $value, $boolean = 'and', $char=null) {
        $operator = 'not between';
        $this->whereColumn($column, $operator, $value, $boolean, $char);

        return $this;
    }

    public function groupBy(...$groups) {
        foreach ($groups as $group) {
            $this->groups = array_merge(
                (array) $this->groups,
                Arr::wrap($group)
            );
        }

        return $this;
    }

    public function having($column, $operator = null, $value = null, $boolean = 'and', $char=null) {
        if(!$this->checkOperation($operator)) {
            $value = $operator;
            $operator = '=';
        }
        $this->havings[] = compact('column', 'operator', 'value', 'boolean', 'char');
        return $this;
    }

    public function orderBy($column, $sort = 'asc') {
        $this->{$this->unions ? 'unionOrders' : 'orders'}[] = [
            'column' => $column,
            'direction' => strtolower($sort) == 'asc' ? 'asc' : 'desc'
        ];

        return $this;
    }

    public function asc($column) {
        $this->{$this->unions ? 'unionOrders' : 'orders'}[] = [
            'column' => $column,
            'sort' => 'asc'
        ];

        return $this;
    }

    public function desc($column) {
        $this->{$this->unions ? 'unionOrders' : 'orders'}[] = [
            'column' => $column,
            'sort' => 'desc'
        ];

        return $this;
    }

    public function offset($value) {
        $this->{$this->unions ? 'unionOffset' : 'offset'} = max(0, $value);

        return $this;
    }

    public function limit($value) {
        if ($value >= 0) {
            $this->{$this->unions ? 'unionLimit' : 'limit'} = $value;
        }

        return $this;
    }

    public function union($query, $all = false) {
        if ($query instanceof Closure) {
            call_user_func($query, $query = $this->newBuilder());
        }

        $this->unions[] = compact('query', 'all');

        $this->addBinding($query->getBindings(), 'union');

        return $this;
    }

    public function unionAll($query)
    {
        return $this->union($query, true);
    }

    public function find($id, $columns = ['*']) {
        return $this->where('id', '=', $id)->first($columns);
    }

    public function get($columns = ['*']) {
        //实现查询方法
    }

    public function first($columns = ['*']) {
        return $this->limit(1)->get($columns);
    }

    public function all() {
        return $this->items;
    }

    public function count() {

    }

    public function pagination() {

    }

    public function insert() {

    }

    public function update() {

    }

    public function increment() {

    }

    public function decrement() {

    }

    public function delete() {

    }

    public function newBuilder() {
        new static();
    }

    public function addBinding($value, $type = 'where') {
        if(!array_key_exists($type, $this->bindings)){
            throw new PropertyNotFoundException(sprintf("binding type %s not found", $type));
        }
        if(is_array($value)) {
            $this->bindings[$type] = array_values(array_merge($this->bindings[$type], $value));
        } else {
            $this->bindings[$type][] = $value;
        }
        return $this;
    }

    public function setBinding($value, $type = 'where') {
        if(!array_key_exists($type, $this->bindings)){
            throw new PropertyNotFoundException(sprintf("binding type %s not found", $type));
        }
        $this->bindings[$type] = $value;
        return $this;
    }

    public function mergeBinding(self $builder) {
        $this->bindings = array_merge_recursive($this->bindings, $builder->bindings);
        return $this;
    }

    public function getBindings()
    {
        return Arr::flatten($this->bindings);
    }

    public function checkOperation($operation) {
        return in_array(strtolower($operation), $this->operations, true);
    }

    public function checkChar($char) {
        if(!is_null($char)){
            $char = str_replace(' ', '', $char);
            if(strlen($char)>0 && (!preg_match("/^[\(]+$/", $char) && !preg_match("/^[\)]+$/", $char))){
                return false;
            }
        }
        return true;
    }
}