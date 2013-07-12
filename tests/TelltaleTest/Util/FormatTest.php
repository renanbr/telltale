<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TelltaleTest\Util;

use Telltale\Telltale;
use Telltale\Util\Format;

class FormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerBytes
     * @covers Telltale\Util\Format::bytes
     */
    public function testBytes($bytes, $expected)
    {
        $this->assertEquals($expected, Format::bytes($bytes));
    }

    public function providerBytes()
    {
        return array(
            array(1001.09, '1,001.09 B'),
            array(1024, '1.00 kB'),
            array(1024 * 2.34, '2.34 kB'),
            array(1024 * 1024, '1.00 MB'),
            array(1024 * 1024 * 3.45, '3.45 MB'),
            array(1024 * 1024 * 1024, '1.00 GB'),
            array(1024 * 1024 * 1024 * 1.07, '1.07 GB'),
        );
    }
    /**
     * @dataProvider providerTime
     * @covers Telltale\Util\Format::time
     */
    public function testTime($seconds, $expected)
    {
        $this->assertEquals($expected, Format::time($seconds));
    }

    public function providerTime()
    {
        return array(
            array(1, '1.000 s'),
            array(100, '100.000 s'),
            array(0.1, '0.100 s'),
            array(0.09, '90.000 ms'),
            array(0.001234, '1.234 ms'),
            array(0.0000019, '0.002 ms'),
            array(0.0000014, '0.001 ms'),
        );
    }
}
