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
 * @covers Telltale\Agent\AbstractTraceCallsAgent
 */
class AbstractTraceCallsAgentTest extends \PHPUnit_Framework_TestCase
{
    public function testEntriesCaughtByParser()
    {
        $agent = $this->getMockForAbstractClass('Telltale\\Agent\\AbstractTraceCallsAgent');
        $reflection = new \ReflectionClass($agent);

        $traceFileAttr = $reflection->getProperty('traceFile');
        $traceFileAttr->setAccessible(true);
        $traceFileAttr->setValue($agent, __DIR__ . '/_files/trace-0.xt');

        $parseMethod = $reflection->getMethod('parse');
        $parseMethod->setAccessible(true);
        $parseMethod->invoke($agent);

        $callsAttr = $reflection->getProperty('calls');
        $callsAttr->setAccessible(true);
        $calls = $callsAttr->getValue($agent);

        $this->assertCount(6, $calls);
    }


    public function testSorting()
    {
        $agent = $this->getMockForAbstractClass('Telltale\\Agent\\AbstractTraceCallsAgent');
        $reflection = new \ReflectionClass($agent);

        $traceFileAttr = $reflection->getProperty('traceFile');
        $traceFileAttr->setAccessible(true);
        $traceFileAttr->setValue($agent, __DIR__ . '/_files/trace-0.xt');

        $parseMethod = $reflection->getMethod('parse');
        $parseMethod->setAccessible(true);
        $parseMethod->invoke($agent);

        $getSortedCallsMethod = $reflection->getMethod('getSortedCalls');
        $getSortedCallsMethod->setAccessible(true);

        $sorted = $getSortedCallsMethod->invoke($agent, 'memory-own');
        reset($sorted);
        $this->assertEquals('str_repeat', key($sorted));

        $sorted = $getSortedCallsMethod->invoke($agent, 'time-own');
        reset($sorted);
        $this->assertEquals('sleep', key($sorted));
    }
}
