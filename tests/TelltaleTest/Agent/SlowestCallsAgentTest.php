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

use Telltale\Agent\SlowestCallsAgent;

/**
 * @covers Telltale\Agent\SlowestCallsAgent
 */
class SlowestCallsAgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerRanking
     */
    public function testRanking($file, array $ranking)
    {
        $agent = new SlowestCallsAgent();
        $agentReflection = new \ReflectionClass($agent);

        $traceFileAttr = $agentReflection->getProperty('traceFile');
        $traceFileAttr->setAccessible(true);
        $traceFileAttr->setValue($agent, $file);

        $startedAttr = $agentReflection->getProperty('started');
        $startedAttr->setAccessible(true);
        $startedAttr->setValue($agent, true);

        $stoppedAttr = $agentReflection->getProperty('stopped');
        $stoppedAttr->setAccessible(true);
        $stoppedAttr->setValue($agent, true);

        $report = $agent->analyse();
        $reportReflection = new \ReflectionClass($report);
        $rowsAttr = $reportReflection->getProperty('rows');
        $rowsAttr->setAccessible(true);
        $rows = $rowsAttr->getValue($report);

        $rankingActual = array();
        foreach ($rows as $i => $row) {
            if ($i > 0) {
                $rankingActual[] = $row[1];
            }
        }

        $this->assertEquals($rankingActual, $ranking);
    }

    public function providerRanking()
    {
        return array(
            'simple' => array(
                __DIR__ . '/_files/trace-0.xt',
                array(
                    'sleep()',
                    'group1_first()',
                    'group2_second()',
                    'group2_first()',
                    'group1_second()',
                ),
            ),
            'deep' => array(
                __DIR__ . '/_files/trace-1.xt',
                array(
                    'sleep()',
                    'critical()',
                    'deep()',
                    'path()',
                    'very()',
                ),
            ),
        );
    }

    /**
     * @dataProvider providerTime
     */
    public function testTime($file, array $time)
    {
        $agent = new SlowestCallsAgent();
        $agentReflection = new \ReflectionClass($agent);

        $traceFileAttr = $agentReflection->getProperty('traceFile');
        $traceFileAttr->setAccessible(true);
        $traceFileAttr->setValue($agent, $file);

        $startedAttr = $agentReflection->getProperty('started');
        $startedAttr->setAccessible(true);
        $startedAttr->setValue($agent, true);

        $stoppedAttr = $agentReflection->getProperty('stopped');
        $stoppedAttr->setAccessible(true);
        $stoppedAttr->setValue($agent, true);

        $report = $agent->analyse();
        $reportReflection = new \ReflectionClass($report);
        $rowsAttr = $reportReflection->getProperty('rows');
        $rowsAttr->setAccessible(true);
        $rows = $rowsAttr->getValue($report);

        $timeActual = array();
        foreach ($rows as $i => $row) {
            if ($i > 0) {
                $timeActual[] = $row[3];
            }
        }

        $this->assertEquals($timeActual, $time);
    }

    public function providerTime()
    {
        return array(
            'simple' => array(
                __DIR__ . '/_files/trace-0.xt',
                array(
                    '3.000 s',
                    '16.814 ms',
                    '0.134 ms',
                    '0.093 ms',
                    '0.074 ms',
                ),
            ),
            'deep' => array(
                __DIR__ . '/_files/trace-1.xt',
                array(
                    '10.001 s',
                    '0.141 ms',
                    '0.137 ms',
                    '0.132 ms',
                    '0.119 ms',
                ),
            ),
        );
    }
}
