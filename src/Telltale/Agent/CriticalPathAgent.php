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
use Telltale\Util\Xdebug\TraceFile;
use Telltale\Util\Format;

class CriticalPathAgent extends AbstractTraceAgent
{
    /**
     * @var array
     */
    protected $tree = array();

    /**
     * Pointer to current call.
     *
     * @var array
     */
    protected $current;

    /**
     * {@inheritdoc}
     */
    public function analyse()
    {
        parent::analyse();

        // calculate critical path
        $this->parse();
        $critical = array();
        $this->extract($this->tree, $critical);

        $report = $this->createReport();
        $root = reset($critical);
        $time = Format::time($root['time']);
        $report->setTitle("Critical path ($time)");
        $report->addRow(
            array(
                '', // position
                'Call',
                'Time',
                'Memory',
                'File',
                'Line',
                '' // type (internal or user defined)
            )
        );
        foreach ($critical as $position => $call) {
            $name = str_repeat('.', $position) . ' ' . $call['name'] . '()';
            $type = $call['is-internal'] ? 'internal' : 'user defined';
            $report->addRow(
                array(
                    (string) $position,
                    trim($name),
                    Format::time($call['time']),
                    Format::bytes($call['memory']),
                    $call['file'],
                    $call['line'],
                    $type
                )
            );
        }

        return $report;
    }

    /**
     * @param array $list
     * @param array &$result
     */
    protected function extract(array $list, array &$result)
    {
        $worst = null;
        foreach ($list as $item) {
            if ($item['time'] && (!$worst || $item['time'] > $worst['time'])) {
                $worst = $item;
            }
        }

        $result[] = array(
            'name'        => $worst['name'],
            'time'        => $worst['time'],
            'memory'      => $worst['memory'],
            'file'        => $worst['file'],
            'line'        => $worst['line'],
            'is-internal' => $worst['is-internal'],
        );

        if ($worst['children']) {
            $this->extract($worst['children'], $result);
        }
    }

    protected function parse()
    {
        $handle = TraceFile::open($this->traceFile);
        while (!feof($handle)) {
            $line = fgets($handle, 4096);
            $parts = explode("\t", trim($line));
            if (count($parts) >= 5) {
                if ('0' == $parts[2]) {
                    $this->parseEntry($parts);
                } elseif ('1' == $parts[2]) {
                    $this->parseExit($parts);
                }
            }
        }
        fclose($handle);
    }

    /**
     * @param array $parts
     */
    protected function parseEntry(array $parts)
    {
        $entry = array(
            'depth'        => $parts[0],
            'name'         => $parts[5],
            'is-internal'  => '0' == $parts[6],
            'file'         => $parts[8],
            'line'         => $parts[9],
            'time-start'   => $parts[3],
            'time'         => -1, // calculated in parseExit()
            'memory'       => -1, // calculated in parseExit()
            'parent'       => null,
            'children'     => array()
        );

        if ($this->current) {
            // append new entry as child of current one
            $this->current['children'][] = &$entry;
            $entry['parent'] =& $this->current;
        } else {
            // append new entry as a root call
            $this->tree[] = &$entry;
        }

        $this->current = &$entry;
    }

    /**
     * @param array $parts
     */
    protected function parseExit(array $parts)
    {
        if ($this->current) {
            $this->current['time'] = $parts[3] - $this->current['time-start'];
            $this->current['memory'] = $parts[4];
            $this->current = &$this->current['parent'];
        }
    }

    /**
     * @return TableReport
     */
    protected function createReport()
    {
        return new TableReport();
    }
}
