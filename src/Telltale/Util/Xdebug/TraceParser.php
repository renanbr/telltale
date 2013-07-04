<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Telltale\Util\Xdebug;

/**
 * This implementation is a copy from
 * https://github.com/xdebug/xdebug/blob/master/contrib/tracefile-analyser.php
 */
class TraceParser
{
    /**
     * @var string
     */
    protected $file = null;

    /**
     * Stores the last call, time and memory for the entry point per
     * stack depth.
     *
     * Structure:
     *     array(
     *         'name'            => integer,
     *         'is-internal'     => boolean,
     *         'time-entry'      => integer,
     *         'time-exit'       => integer,
     *         'time-children'   => integer,
     *         'memory-entry'    => integer,
     *         'memory-exit'     => integer,
     *         'memory-children' => integer
     *     )
     *
     * @var array
     */
    protected $stack = array();

    /**
     * Stores per call the total time and memory increases and calls.
     *
     * Structure:
     *     array(
     *         'name'             => integer,
     *         'is-internal'      => boolean,
     *         'times'            => integer,
     *         'time-inclusive'   => integer,
     *         'time-children'    => integer,
     *         'time-own'         => integer,
     *         'memory-inclusive' => integer,
     *         'memory-children'  => integer,
     *         'memory-own'       => integer
     *     )
     *
     * @var array
     */
    protected $calls = array();

    /**
     * Used to remove unwanted executions from statistics, specially internal
     * Telltale calls.
     *
     * @var integer
     */
    protected $ignoring = 0;

    /**
     * @var TraceParser[]
     */
    protected static $instances = array();

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @param string $file
     * @return TraceParser
     */
    public static function factory($file)
    {
        if (!isset(self::$instances[$file])) {
            self::$instances[$file] = new self($file);
        }

        return self::$instances[$file];
    }

    /**
     * @param string $file
     * @throws \RuntimeException
     */
    public static function validateFile($file)
    {
        $handle = fopen($file, 'r');
        if (!$handle) {
            throw new \RuntimeException("Can't open '$file'.");
        }

        $header1 = fgets($handle, 4096);
        $header2 = fgets($handle, 4096);
        fclose($handle);

        if (!preg_match('@Version: 2.*@', $header1) ||
        !preg_match('@File format: 2@', $header2)
        ) {
            throw new \RuntimeException("File '$file' is not an Xdebug trace file made with format option '1'.");
        }
    }

    /**
     * @param string $sortKey
     * @return array
     */
    public function getCalls($sortKey = null)
    {
        $result = $this->calls;
        if ($sortKey !== null) {
            uasort(
                $result,
                function($a, $b) use ($sortKey) {
                    return ($a[$sortKey] > $b[$sortKey]) ? -1 : ($a[$sortKey] < $b[$sortKey] ? 1 : 0);
                }
            );
        }

        return $result;
    }

    public function parse()
    {
        if ($this->calls) {
            return;
        }

        // reset stack
        $this->stack = array();
        static::validateFile($this->file);
        $handle = fopen($this->file, 'r');
        while (!feof($handle)) {
            $line = fgets($handle, 4096);
            $parts = explode("\t", trim($line));
            if (count($parts) < 5) {
                continue;
            }

            $isEntry = '0' == $parts[2];
            $isExit = '1' == $parts[2];
            if ($isEntry) {
                $this->parseEntryLine($parts);
            } else if ($isExit) {
                $this->parseExitLine($parts);
            }
        }
        fclose($handle);

        // calculate own time, and own memory
        foreach ($this->calls as &$call) {
            $call['time-own'] = $call['time-inclusive'] - $call['time-children'];
            $call['memory-own'] = $call['memory-inclusive'] - $call['memory-children'];
        }
    }

    /**
     * @param array $parts
     */
    protected function parseEntryLine(array $parts)
    {
        // basic data
        $depth = $parts[0];
        $time = $parts[3];
        $memory = $parts[4];

        // make sure that exists parents, see normalizeStacks()
        $this->normalizeStacks($depth - 1);

        $name = $parts[5];
        $entry = array(
            'name'            => $name,
            'is-internal'     => '0' == $parts[6],
            'time-entry'      => $time,
            'time-exit'       => 0,
            'time-children'   => 0,
            'memory-entry'    => $memory,
            'memory-exit'     => 0,
            'memory-children' => 0
        );

        // save new entry
        $this->stack[$depth] = $entry;

        // remove library from statistics
        if ($this->isInternalCall($name)) {
            $this->ignoring++;
        }
    }

    /**
     * @param array $parts
     */
    protected function parseExitLine(array $parts)
    {
        // basic data
        $depth = $parts[0];
        $time = $parts[3];
        $memory = $parts[4];

        // make sure there are entry for current exit call, see normalizeStacks()
        $this->normalizeStacks($depth);

        $entry = &$this->stack[$depth];
        $entry['time-exit'] = $time;
        $entry['memory-exit'] = $memory;

        // push deltas to parent
        $parent = &$this->stack[$depth - 1];
        $parent['time-children'] += $entry['time-exit'] - $entry['time-entry'];
        $parent['memory-children'] += $entry['memory-exit'] - $entry['memory-entry'];

        if (0 == $this->ignoring) {
            // make sure it is not a phantom call, see normalizeStacks()
            if ('' != $entry['name']) {
                $this->addToCall($entry);
            }
        }

        if ($this->isInternalCall($entry['name'])) {
            $this->ignoring--;
        }
    }

    /**
     * Used for fill stacks with empty values.
     *
     * Trace file may contains partial tracing.
     *
     * @param integer $depth
     */
    protected function normalizeStacks($depth)
    {
        $entry = array(
            'name'            => '',
            'is-internal'     => false,
            'time-entry'      => 0,
            'time-exit'       => 0,
            'time-children'   => 0,
            'memory-entry'    => 0,
            'memory-exit'     => 0,
            'memory-children' => 0
        );

        for ($i = -1; $i <= $depth; $i++) {
            if (!isset($this->stack[$i])) {
                $this->stack[$i] = $entry;
            }
        }
    }

    /**
     * @param string $name
     * @return boolean
     */
    protected function isInternalCall($name)
    {
        if (!strpos($name, '->') && !strpos($name, '::')) {
            return false;
        }
        $parts = explode('\\', $name);

        return 'Telltale' == reset($parts);
    }

    /**
     * @param array $entry
     */
    protected function addToCall(array $entry)
    {
        $name = $entry['name'];
        if (!isset($this->calls[$name])) {
            $this->calls[$name] = array(
                'times'            => 0,
                'is-internal'      => $entry['is-internal'],
                'time-inclusive'   => 0,
                'time-children'    => 0,
                'memory-inclusive' => 0,
                'memory-children'  => 0
            );
        }

        $call = &$this->calls[$name];
        $call['times']++;
        $call['time-inclusive'] += $entry['time-exit'] - $entry['time-entry'];
        $call['time-children'] += $entry['time-children'];
        $call['memory-inclusive'] += $entry['memory-exit'] - $entry['memory-entry'];
        $call['memory-children'] += $entry['memory-children'];
    }
}
