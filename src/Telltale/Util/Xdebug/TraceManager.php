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
     * Returns the name of the file which is used to trace.
     *
     * @return string
     */
    public static function start()
    {
        if (!self::$file) {
            if (xdebug_get_tracefile_name()) {
                throw new \RuntimeException('Can not start tracing, it has already been started.');
            }
            $file = tempnam(sys_get_temp_dir(), 'telltale');
            xdebug_start_trace($file, \XDEBUG_TRACE_COMPUTERIZED);
            self::$file = $file . '.xt';
        }
        return self::$file;
    }

    public static function stop()
    {
        if (self::$file) {
            xdebug_stop_trace();
            self::$file = null;
        }
    }
}
