<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Telltale\Util\Xdebug;

abstract class TraceManager
{
    /**
     * @var string
     */
    protected static $file;

    /**
     * @return string File path to trace file
     */
    public static function start()
    {
        if (!xdebug_is_enabled()) {
            xdebug_enable();
        }
        if (!self::$file) {
            self::$file = tempnam(sys_get_temp_dir(), 'telltale');
            xdebug_start_trace(self::$file, \XDEBUG_TRACE_COMPUTERIZED);
        }
        return self::$file . '.xt';
    }

    public static function stop()
    {
        if (self::$file) {
            xdebug_stop_trace();
            self::$file = null;
        }
    }
}
