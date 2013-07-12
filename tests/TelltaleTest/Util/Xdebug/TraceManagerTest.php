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

use Telltale\Util\Xdebug\TraceManager;

/**
 * @cover Telltale\Util\Xdebug\TraceManager
 */
class TraceManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $stopTraceOnTearDown = false;

    protected function tearDown()
    {
        if ($this->stopTraceOnTearDown) {
            xdebug_stop_trace();
            $this->stopTraceOnTearDown = false;
        }
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Can not start tracing, it has already been started.
     */
    public function testCanNotStartWhenStartedByThird()
    {
        xdebug_start_trace();
        $this->stopTraceOnTearDown = true;
        $manager = new TraceManager();
        $manager->start();
    }

    public function testBasic()
    {
        $manager = new TraceManager();

        ltrim('some text');
        $file = $manager->start();
        substr_compare('abcde', 'bc', 1, 2);
        $manager->stop();
        rtrim('some text');

        $contents = file_get_contents($file);
        $this->assertNotContains("\tltrim\t", $contents);
        $this->assertContains("\tsubstr_compare\t", $contents);
        $this->assertNotContains("\trtrim\t", $contents);
    }

    public function testWhenStartedTwiceThroughApiMustReturnSameFile()
    {
        $manager = new TraceManager();
        $file1 = $manager->start();
        $file2 = $manager->start();
        $this->assertEquals($file1, $file2);
        $manager->stop();
    }
}
