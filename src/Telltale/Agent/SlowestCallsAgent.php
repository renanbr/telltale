<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Telltale\Agent;

use Telltale\Util\Xdebug\TraceManager;
use Telltale\Util\Xdebug\TraceParser;
use Telltale\Report\TableReport;

class SlowestCallsAgent extends AbstractAgent
{
    /**
     * @var string
     */
    protected $traceFile;

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        parent::start();
        $this->traceFile = TraceManager::start();
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        parent::stop();
        TraceManager::stop();
    }

    /**
     * {@inheritdoc}
     */
    public function analyse()
    {
        parent::analyse();

        $parser = TraceParser::factory($this->traceFile);
        $parser->parse();
        $calls = array_slice($parser->getCalls('time-own'), 0, 5);

        $report = new TableReport();
        $report->setTitle('Slowest calls');
        $report->addRow(
            array(
                '', // position
                'Call',
                '# of Calls',
                'Own Time',
                'Including Children',
                '' // type (internal or user defined)
            )
        );
        $position = 0;
        foreach ($calls as $name => $call) {
            if ($call['is-internal']) {
                $type = 'internal';
                $inclusive = '-';
            } else {
                $type = 'user defined';
                $inclusive = static::formatTime($call['time-inclusive']);
            }
            $report->addRow(
                array(
                    (string) $position++,
                    $name . '()',
                    (string) $call['times'],
                    static::formatTime($call['time-own']),
                    $inclusive,
                    $type
                )
            );
        }

        $report->spread();
    }
}
