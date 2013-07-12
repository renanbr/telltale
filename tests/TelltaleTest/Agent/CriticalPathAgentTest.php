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

use Telltale\Agent\CriticalPathAgent;

/**
 * @covers Telltale\Agent\CriticalPathAgent
 */
class CriticalPathAgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerAnalyse
     */
    public function testAnalyse($file, array $path)
    {
        $agent = new CriticalPathAgent();
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

        $pathActual = array();
        foreach ($rows as $i => $row) {
            if ($i > 0) {
                $pathActual[] = $row[1];
            }
        }

        $this->assertEquals($pathActual, $path);
    }

    public function providerAnalyse()
    {
        return array(
            'simple' => array(
                __DIR__ . '/_files/trace-0.xt',
                array(
                    'group2_first()',
                    '. sleep()',
                ),
            ),
            'deep' => array(
                __DIR__ . '/_files/trace-1.xt',
                array(
                    'thisIsA()',
                    '. very()',
                    '.. deep()',
                    '... critical()',
                    '.... path()',
                    '..... sleep()',
                ),
            ),
        );
    }
}
