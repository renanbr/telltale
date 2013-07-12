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

use Telltale\Report\TextReport;

/**
 * @covers Telltale\Report\TextReport
 */
class TextReportTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateLoggerContainsFirePhpHandler()
    {
        $report = new TextReport();
        $report->setText('some text');

        $reflection = new \ReflectionClass($report);
        $method = $reflection->getMethod('createLogger');
        $method->setAccessible(true);

        $hasFirePhp = array();
        $logger = $method->invoke($report);
        try {
            while ($handler = $logger->popHandler()) {
                $hasFirePhp[] = is_a($handler, 'Telltale\\Util\\Monolog\\Handler\\FirePhpHandler');
            }
        } catch (\LogicException $e) {
        }

        $this->assertContains(
            true,
            $hasFirePhp,
            'TextReport should create a Logger which contains a FirePhpHandler'
        );
    }

    public function testDataSentToLogger()
    {
        $text = 'nice text';

        $logger = $this->getMockBuilder('Monolog\\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects($this->once())
            ->method('info')
            ->with($this->identicalTo($text));

        $report = $this->getMock(
            'Telltale\\Report\\TextReport',
            array('createLogger')
        );
        $report
            ->expects($this->once())
            ->method('createLogger')
            ->will($this->returnValue($logger));

        $report->setText($text);
        $report->spread();
    }
}
