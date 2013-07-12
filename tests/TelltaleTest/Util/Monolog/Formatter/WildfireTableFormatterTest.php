<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TelltaleTest\Util\Monolog\Formatter;

use Telltale\Util\Monolog\Formatter\WildfireTableFormatter;
use Monolog\Logger;
use Monolog\Formatter\WildfireFormatter;

/**
 * @cover Telltale\Util\Monolog\Formatter\WildfireTableFormatter
 */
class WildfireTableFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultFormat()
    {
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array('from' => 'logger'),
            'datetime' => new \DateTime("@0"),
            'extra' => array('ip' => '127.0.0.1'),
            'message' => 'log',
        );

        $monologFormatter = new WildfireFormatter();
        $monologResult = $monologFormatter->format($record);

        $telltaleFormatter = new WildfireTableFormatter();
        $telltaleResult = $telltaleFormatter->format($record);

        $this->assertEquals($monologResult, $telltaleResult);
    }

    public function testContextEffectivity()
    {
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array(
                'from' => 'logger',
                'wildfire-table' => array(
                    array('cell-0-0', 'cell-0-1'),
                    array('cell-1-0', 'cell-1-1'),
                ),
            ),
            'datetime' => new \DateTime("@0"),
            'extra' => array('ip' => '127.0.0.1'),
            'message' => 'log',
        );

        $monologFormatter = new WildfireFormatter();
        $monologResult = $monologFormatter->format($record);

        $telltaleFormatter = new WildfireTableFormatter();
        $telltaleResult = $telltaleFormatter->format($record);

        $this->assertNotEquals($monologResult, $telltaleResult);
    }

    public function testTableOutput()
    {
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array(
                'from' => 'logger',
                'wildfire-table' => array(
                    array('cell-a-0', 'cell-a-1'),
                    array('cell-b-0', 'cell-b-1'),
                ),
            ),
            'datetime' => new \DateTime("@0"),
            'extra' => array('ip' => '127.0.0.1'),
            'message' => 'log',
        );

        $formatter = new WildfireTableFormatter();
        $result = $formatter->format($record);
        $expected = '107|[{"Type":"TABLE","File":"","Line":"","Label":"meh: log"},'
                  . '[["cell-a-0","cell-a-1"],["cell-b-0","cell-b-1"]]]|';

        $this->assertEquals($expected, $result);
    }
}
