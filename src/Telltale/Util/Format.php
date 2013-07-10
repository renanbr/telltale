<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Telltale\Util;

abstract class Format
{
    /**
     * @param integer $bytes
     * @return string
     */
    public static function bytes($bytes)
    {
        $factor = $bytes < 0 ? -1 : 1;
        $value = abs($bytes);
        $prefixes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        foreach ($prefixes as $prefix) {
            if ($value < 1024) {
                break;
            }
            $value /= 1024;
        }
        return number_format($value * $factor, 2) . ' ' . $prefix;
    }

    /**
     * @param float $seconds
     * @return string
     */
    public static function time($seconds)
    {
        $factor = $seconds < 0 ? -1 : 1;
        $value = abs($seconds);
        if ($value < 0.1) {
            $value *= 1000;
            $prefix = 'ms';
        } else {
            $prefix = 's';
        }
        return number_format($value * $factor, 3) . ' ' . $prefix;
    }
}
