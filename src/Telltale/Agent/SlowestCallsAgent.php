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

use Telltale\Report\TableReport;
use Telltale\Util\Format;

class SlowestCallsAgent extends AbstractTraceCallsAgent
{
    /**
     * {@inheritdoc}
     */
    public function analyse()
    {
        parent::analyse();

        $this->parse();
        $calls = array_slice($this->getSortedCalls('time-own'), 0, 5);

        $report = $this->createReport();
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
                $inclusive = Format::time($call['time-inclusive']);
            }
            $report->addRow(
                array(
                    (string) $position++,
                    $name . '()',
                    (string) $call['times'],
                    Format::time($call['time-own']),
                    $inclusive,
                    $type
                )
            );
        }

        return $report;
    }

    /**
     * @return TableReport
     */
    protected function createReport()
    {
        return new TableReport();
    }
}
