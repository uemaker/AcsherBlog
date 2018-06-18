<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\base;
use acsher\exception\MethodNotFoundException;
use Closure;
use ReflectionClass;
use ReflectionMethod;

/**
 * Mixable trait
 *
 * @author felybo
 * @since 1.0
 */
trait Mixable
{

    protected static $mixins = [];

    /**
     * @param $mixin
     */
    public static function mixin($mixin)
    {
        $methods = (new ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );
        foreach ($methods as $method) {
            $method->setAccessible(true);
            static::_mixin($method->name, $method->invoke($mixin));
        }
    }

    private static function _mixin($name, $method)
    {
        static::$mixins[$name] = $method;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function hasMixin($name)
    {
        return isset(static::$mixins[$name]);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        if(!static::hasMixin($name)) {
            throw new MethodNotFoundException(sprintf('Method %s::%s does not exist.', static::class, $name));
        }
        $method = static::$mixins[$name];
        if ($method instanceof Closure) {
            return call_user_func_array($method->bindTo($this, static::class), $arguments);
        }

        return call_user_func_array($method, $arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws MethodNotFoundException
     */
    public static function __callStatic($name, $arguments)
    {
        if(!static::hasMixin($name)) {
            throw new MethodNotFoundException(sprintf('Method %s::%s does not exist.', static::class, $name));
        }
        if (static::$mixins[$name] instanceof Closure) {
            return call_user_func_array(Closure::bind(static::$mixins[$name], null, static::class), $arguments);
        }

        return call_user_func_array($name, $arguments);
    }
}