<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\cache;

use acsher\base\Base;


/**
 * Cache class
 *
 * @author felybo
 * @since 1.0
 */
abstract class Cache extends Base implements CacheInterface
{
    public $prefix = '';

    public $defaultDuration = 0;

    public function generatePrefix($prefix)
    {
        // TODO: Implement generatePrefix() method.
    }

    public function get($key)
    {
        // TODO: Implement get() method.
    }

    public function multiGet($keys)
    {
        // TODO: Implement multiGet() method.
    }

    public function set($key, $value, $duration = 0)
    {
        // TODO: Implement set() method.
    }

    public function multiSet($values, $duration = 0)
    {
        // TODO: Implement multiSet() method.
    }

    public function exists($key)
    {
        // TODO: Implement exists() method.
    }

    public function increase($key)
    {
        // TODO: Implement increase() method.
    }

    public function decrease($key)
    {
        // TODO: Implement decrease() method.
    }

    public function remove($key)
    {
        // TODO: Implement remove() method.
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }

    abstract protected function getValue($key);

    abstract protected function setValue($key, $value, $duration);

    abstract protected function removeValue($key);

    abstract protected function flushValue();

}