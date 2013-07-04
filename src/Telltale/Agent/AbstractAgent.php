<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Telltale\Agent;

abstract class AbstractAgent implements AgentInterface
{
    /**
     * @param integer $bytes
     * @return string
     */
    protected static function formatBytes($bytes)
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
     * @param integer $seconds
     * @return string
     */
    protected static function formatTime($seconds)
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
