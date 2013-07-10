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

abstract class TraceFile
{
    /**
     * @param string $file
     * @throws \RuntimeException
     * @return resource
     */
    public static function open($file)
    {
        $handle = fopen($file, 'r');
        if (!$handle) {
            throw new \RuntimeException("Can't open '$file' trace file.");
        }

        $header1 = fgets($handle, 4096);
        $header2 = fgets($handle, 4096);

        if (!preg_match('@Version: 2.*@', $header1) ||
            !preg_match('@File format: 2@', $header2)
        ) {
            throw new \RuntimeException("File '$file' is not an Xdebug trace file made with format option '1'.");
        }

        return $handle;
    }
}
