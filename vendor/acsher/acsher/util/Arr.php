<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\util;

use acsher\base\Mixable;


/**
 * Arr class
 *
 * @author felybo
 * @since 1.0
 */
class Arr
{
    use Mixable;

    public static function add()
    {
    }

    public static function set(&$array, $key, $value)
    {
    }

    public static function arr2Obj($arr)
    {

    }

    public static function wrap($value)
    {
        if (is_null($value)) {
            return [];
        }

        return ! is_array($value) ? [$value] : $value;
    }

    public static function flatten($array, $depth = INF)
    {
        $result = [];

        foreach ($array as $item) {
            if (! is_array($item)) {
                $result[] = $item;
            } elseif ($depth === 1) {
                $result = array_merge($result, array_values($item));
            } else {
                $result = array_merge($result, static::flatten($item, $depth - 1));
            }
        }

        return $result;
    }
}