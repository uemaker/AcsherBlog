<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\cache;


/**
 * CacheInterface interface
 *
 * @author felybo
 * @since 1.0
 */
interface CacheInterface
{
    public function generatePrefix($prefix);

    public function get($key);

    public function multiGet($keys);

    public function set($key, $value, $duration = 0);

    public function multiSet($values, $duration = 0);

    public function exists($key);

    public function increase($key);

    public function decrease($key);

    public function remove($key);

    public function flush();
}