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

use Telltale\Report\TableReport;

/**
 * @covers Telltale\Report\TableReport
 */
class TableReportTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFirePhpLoggerUseSpecialFormatter()
    {
        $report = new TableReport();
        $report->setTitle('some title');
        $report->addRow(array('cell 0', 'cell 1'));

        $reflection = new \ReflectionClass($report);
        $method = $reflection->getMethod('createLogger');
        $method->setAccessible(true);

        $logger = $method->invoke($report);

        $chromePhpHandler = $logger->popHandler();
        $chromePhpFormatter = $chromePhpHandler->getFormatter();
        $this->assertInstanceOf(
            'Telltale\\Util\\Monolog\\Formatter\\ChromePhpTableFormatter',
            $chromePhpFormatter
        );

        $firePhpHandler = $logger->popHandler();
        $firePhpFormatter = $firePhpHandler->getFormatter();
        $this->assertInstanceOf(
            'Telltale\\Util\\Monolog\\Formatter\\WildfireTableFormatter',
            $firePhpFormatter
        );
    }

    public function testFirePhpDataSentToLogger()
    {
        $title = 'nice title';
        $table = array(
            array('cell 1', 'cell 2'),
            array('cell 3', 'cell 4'),
        );

        $logger = $this->getMockBuilder('Monolog\\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        $logger
            ->expects($this->once())
            ->method('info')
            ->with(
                $this->identicalTo($title),
                $this->identicalTo(array('telltale-table' => $table))
            );

        $report = $this->getMock(
            'Telltale\\Report\\TableReport',
            array('createLogger')
        );
        $report
            ->expects($this->once())
            ->method('createLogger')
            ->will($this->returnValue($logger));

        $report->setTitle($title);
        $report->addRow($table[0]);
        $report->addRow($table[1]);
        $report->spread();
    }
}
