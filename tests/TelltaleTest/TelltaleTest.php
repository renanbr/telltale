<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TelltaleTest;

use Telltale\Telltale;

/**
 * @backupStaticAttributes enabled
 * @covers Telltale\Telltale
 */
class TelltaleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Telltale instance can not be started twice.
     */
    public function testCanNotStartTwice()
    {
        $telltale = new Telltale();
        $telltale->start();
        $telltale->start();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Telltale instance was not started.
     */
    public function testCanNotStopIfNotStarted()
    {
        $telltale = new Telltale();
        $telltale->stop();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Telltale instance is already stopped.
     */
    public function testCanNotStopTwice()
    {
        $telltale = new Telltale();
        $telltale->start();
        $telltale->stop();
        $telltale->stop();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage There is another Telltale instance running.
     */
    public function testCanNotRunInParallel()
    {
        $telltale1 = new Telltale();
        $telltale2 = new Telltale();
        $telltale1->start();
        $telltale2->start();
    }

    public function testAgentStartTrigger()
    {
        $agent = $this->getMock('Telltale\\Agent\\AgentInterface');
        $agent
            ->expects($this->once())
            ->method('start');

        $telltale = new Telltale();
        $telltale->pushAgent($agent);
        $telltale->start();
        $telltale->popAgent();
        $telltale->stop();
    }

    public function testAgentStopTrigger()
    {
        $report = $this->getMock('Telltale\\Report\\ReportInterface');

        $agent = $this->getMock('Telltale\\Agent\\AgentInterface');
        $agent
            ->expects($this->once())
            ->method('analyse')
            ->will($this->returnValue($report));

        $telltale = new Telltale();
        $telltale->pushAgent($agent);
        $telltale->start();
        $telltale->stop();
    }
}
