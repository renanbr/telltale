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

use Telltale\Agent\MemoryUsageCallsAgent;

/**
 * @covers Telltale\Agent\MemoryUsageCallsAgent
 */
class MemoryUsageCallsAgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerRanking
     */
    public function testRanking($file, array $ranking)
    {
        $agent = new MemoryUsageCallsAgent();
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
                    'str_repeat()',
                    'group2_first()',
                    'group2_second()',
                    'group1_first()',
                    'sleep()',
                ),
            ),
            'deep' => array(
                __DIR__ . '/_files/trace-1.xt',
                array(
                    'path()',
                    'very()',
                    'deep()',
                    'critical()',
                    'thisIsA()',
                ),
            ),
        );
    }

    /**
     * @dataProvider providerMemory
     */
    public function testMemory($file, array $memory)
    {
        $agent = new MemoryUsageCallsAgent();
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

        $memoryActual = array();
        foreach ($rows as $i => $row) {
            if ($i > 0) {
                $memoryActual[] = $row[3];
            }
        }

        $this->assertEquals($memoryActual, $memory);
    }

    public function providerMemory()
    {
        return array(
            'simple' => array(
                __DIR__ . '/_files/trace-0.xt',
                array(
                    '12.09 kB',
                    '32.00 B',
                    '32.00 B',
                    '32.00 B',
                    '0.00 B',
                ),
            ),
            'deep' => array(
                __DIR__ . '/_files/trace-1.xt',
                array(
                    '80.00 B',
                    '40.00 B',
                    '40.00 B',
                    '40.00 B',
                    '32.00 B',
                ),
            ),
        );
    }
}
