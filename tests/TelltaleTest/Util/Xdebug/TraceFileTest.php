<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TelltaleTest\Util\Xdebug;

use Telltale\Util\Xdebug\TraceFile;

/**
 * @cover Telltale\Util\Xdebug\TraceFile
 */
class TraceFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Can not open trace file given.
     */
    public function testFailWhenFileDoesNotExist()
    {
        TraceFile::open(__DIR__ . '/_files/not-exists.xt');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage File given is not an Xdebug trace file made with format option '2'.
     */
    public function testFormatInvalid()
    {
        TraceFile::open(__DIR__ . '/_files/invalid-trace.xt');
    }

    public function testValidFile()
    {
        $handle = TraceFile::open(__DIR__ . '/_files/trace.xt');
        $this->assertInternalType('resource', $handle);
        fclose($handle);
    }
}
