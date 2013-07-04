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

class CriticalPathAgent extends AbstractAgent
{
    /**
     * @var string
     */
    protected $traceFile;

    /**
     * @var array
     */
    protected $tree = array();

    /**
     * Pointer to current
     *
     * @var array
     */
    protected $current;

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        if (!$this->traceFile) {
            $this->traceFile = TraceManager::start();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        TraceManager::stop();
    }

    /**
     * {@inheritdoc}
     */
    public function analyse()
    {
        if (!$this->traceFile) {
            return;
        }

        $this->parse();
        $critical = array();
        $this->extract($this->tree, $critical);
        if (!$critical) {
            return;
        }

        $report = new TableReport();
        $root = reset($critical);
        $time = static::formatTime($root['time']);
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
                    $name,
                    static::formatTime($call['time']),
                    static::formatBytes($call['memory']),
                    $call['file'],
                    $call['line'],
                    $type
                )
            );
        }

        $report->spread();
    }

    /**
     * @param array $list
     * @param array $result
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
        TraceParser::validateFile($this->traceFile);
        $handle = fopen($this->traceFile, 'r');
        while (!feof($handle)) {
            // check line
            $line = fgets($handle, 4096);
            $parts = explode("\t", trim($line));
            if (count($parts) < 5) {
                continue;
            }

            // parse by type
            if ('0' == $parts[2]) {
                $this->parseEntry($parts);
            } else if ('1' == $parts[2]) {
                $this->parseExit($parts);
            }
        }
        fclose($handle);
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
}
