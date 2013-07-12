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

use Telltale\Agent\MemoryPeakAgent;

/**
 * @covers Telltale\Agent\MemoryPeakAgent
 */
class MemoryPeakAgentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerAnalyse
     */
    public function testAnalyse($file, $text)
    {
        $agent = new MemoryPeakAgent();
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
        $textAttr = $reportReflection->getProperty('text');
        $textAttr->setAccessible(true);
        $textActual = $textAttr->getValue($report);

        $this->assertEquals($textActual, $text);
    }

    public function providerAnalyse()
    {
        return array(
            'simple' => array(
                __DIR__ . '/_files/trace-0.xt',
                'Memory peak 234.84 kB at str_repeat() in /var/www/trace.php on line 17',
            ),
            'deep' => array(
                __DIR__ . '/_files/trace-1.xt',
                'Memory peak 226.34 kB at sleep() in /var/www/deep-path.php on line 35',
            ),
        );
    }
}
