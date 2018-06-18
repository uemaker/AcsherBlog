<?php
/**
 * @link http://www.acsher.com
 * @copyright Copyright (c) 2018 acsher.com
 */

namespace acsher\log;


/**
 * LogInterface interface
 *
 * @author felybo
 * @since 1.0
 */
interface LogInterface
{
    public function init();

    public function log($message, $level, $tag);

    public function tract($message, $level, $tag);

    public function flush();
}