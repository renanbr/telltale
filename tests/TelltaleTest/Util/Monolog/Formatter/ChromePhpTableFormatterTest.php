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

use Telltale\Util\Monolog\Formatter\ChromePhpTableFormatter;
use Monolog\Logger;
use Monolog\Formatter\ChromePHPFormatter;

/**
 * @cover Telltale\Util\Monolog\Formatter\ChromePhpTableFormatter
 */
class ChromePhpTableFormatterTest extends \PHPUnit_Framework_TestCase
{

    public function testDefaultFormat()
    {
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array(
                'from' => 'logger'
            ),
            'datetime' => new \DateTime("@0"),
            'extra' => array(
                'ip' => '127.0.0.1'
            ),
            'message' => 'log'
        );

        $monologFormatter = new ChromePHPFormatter();
        $monologResult = $monologFormatter->format($record);

        $telltaleFormatter = new ChromePhpTableFormatter();
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
                'telltale-table' => array(
                    array(
                        'cell-0-0',
                        'cell-0-1'
                    ),
                    array(
                        'cell-1-0',
                        'cell-1-1'
                    )
                )
            ),
            'datetime' => new \DateTime("@0"),
            'extra' => array(
                'ip' => '127.0.0.1'
            ),
            'message' => 'log'
        );

        $monologFormatter = new ChromePHPFormatter();
        $monologResult = $monologFormatter->format($record);

        $telltaleFormatter = new ChromePhpTableFormatter();
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
                'telltale-table' => array(
                    array(
                        'cell-a-0',
                        'cell-a-1'
                    ),
                    array(
                        'cell-b-0',
                        'cell-b-1'
                    )
                )
            ),
            'datetime' => new \DateTime("@0"),
            'extra' => array(
                'ip' => '127.0.0.1'
            ),
            'message' => 'log'
        );

        $formatter = new ChromePhpTableFormatter();
        $result = $formatter->format($record);

        $this->assertNull($result[0]);

        // removes first row, it is used as table header
        $table = $result[1];
        array_shift($table);
        $this->assertEquals($table, $record['context']['telltale-table']);
    }
}
