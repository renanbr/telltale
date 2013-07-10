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

class MemoryUsageCallsAgent extends AbstractTraceCallsAgent
{
    /**
     * {@inheritdoc}
     */
    public function analyse()
    {
        parent::analyse();

        $this->parse();
        $calls = array_slice($this->getSortedCalls('memory-own'), 0, 5);

        $report = new TableReport();
        $report->setTitle('Top memory usage calls');
        $report->addRow(
            array(
                '', // position
                'Call',
                '# of Calls',
                'Own Memory',
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
                $inclusive = Format::bytes($call['memory-inclusive']);
            }
            $report->addRow(
                array(
                    (string) $position++,
                    $name . '()',
                    (string) $call['times'],
                    Format::bytes($call['memory-own']),
                    $inclusive,
                    $type
                )
            );
        }

        $report->spread();
    }
}
