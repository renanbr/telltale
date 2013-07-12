<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TelltaleTest\Report;

/**
 * @covers Telltale\Agent\AbstractAgent
 */
class AbstractAgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Telltale Agent can not be started twice.
     */
    public function testCanNotStartTwice()
    {
        $agent = $this->getMockForAbstractClass('Telltale\\Agent\\AbstractAgent');
        $agent->start();
        $agent->start();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Can not stop Telltale Agent, it was not started.
     */
    public function testCanNotStopWhenNotStarted()
    {
        $agent = $this->getMockForAbstractClass('Telltale\\Agent\\AbstractAgent');
        $agent->stop();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Telltale Agent is already stopped.
     */
    public function testCanNotStopTwice()
    {
        $agent = $this->getMockForAbstractClass('Telltale\\Agent\\AbstractAgent');
        $agent->start();
        $agent->stop();
        $agent->stop();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Telltale Agent can not analyse, it was not started.
     */
    public function testCanNotAnalyseWhenNotStarted()
    {
        $agent = $this->getMockForAbstractClass('Telltale\\Agent\\AbstractAgent');
        $agent->analyse();
    }
}
