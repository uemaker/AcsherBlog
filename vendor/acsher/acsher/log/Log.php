<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\log;

use acsher\base\Base;


/**
 * Log class
 *
 * @author felybo
 * @since 1.0
 */
abstract class Log extends Base implements LogInterface
{

    const LEVEL_DEBUG = 0x01;
    const LEVEL_INFO = 0x02;
    const LEVEL_WARN = 0x03;
    const LEVEL_ERROR = 0x04;

    public $messages = [];

    public $tractLevel = 0;


    public function init()
    {
        // TODO: Implement init() method.
    }

    public function log($message, $level = 0, $tag = 'App')
    {
        // TODO: Implement log() method.
    }

    public function tract($message, $level = 0, $tag = 'App')
    {
        // TODO: Implement tract() method.
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }

    abstract protected function writeLog($message, $level, $category);

    abstract protected function flushLog();
}