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

use Telltale\Report\TextReport;
use Telltale\Util\Xdebug\TraceFile;
use Telltale\Util\Format;

class MemoryPeakAgent extends AbstractTraceAgent
{
    /**
     * {@inheritdoc}
     */
    public function analyse()
    {
        parent::analyse();

        list($peak, $call, $file, $line) = $this->parse();

        $memory = Format::bytes($peak);
        $details = null;
        if ($call) {
            $details = ' at ' . $call . '() in ' . $file . ' on line ' . $line;
        }

        $report = $this->createReport();
        $report->setText('Memory peak ' . $memory . $details);

        return $report;
    }

    /**
     * @return array
     */
    protected function parse()
    {
        $peak = -1;
        $call = null;
        $file = null;
        $line = null;
        $entries = array();
        $handle = TraceFile::open($this->traceFile);
        while (!feof($handle)) {
            // check line
            $buffer = fgets($handle, 4096);
            $parts = explode("\t", trim($buffer));
            if (count($parts) < 5) {
                continue;
            }

            // save entry
            $level = $parts[0];
            if ('0' == $parts[2]) {
                $entries[$level] = $parts;
            }

            // save peak
            $memory = $parts[4];
            if ($memory > $peak) {
                $peak = $memory;
                if (isset($entries[$level])) {
                    $call = $entries[$level][5];
                    $file = $entries[$level][8];
                    $line = $entries[$level][9];
                } else {
                    $call = null;
                    $file = null;
                    $line = null;
                }
            }
        }
        fclose($handle);
        return array($peak, $call, $file, $line);
    }

    /**
     * @return TextReport
     */
    protected function createReport()
    {
        return new TextReport();
    }
}
