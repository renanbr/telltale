<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Telltale;

use Telltale\Agent\AgentInterface;

class Telltale
{
    /**
     * Pointer to current instance running.
     *
     * @var Telltale
     */
    protected static $running;

    /**
     * @var AgentInterface[]
     */
    protected $agents = array();

    /**
     * @var boolean
     */
    protected $started = false;

    /**
     * @var boolean
     */
    protected $stopped = false;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function __destruct()
    {
        if ($this->started && !$this->stopped) {
            $this->stop();
        }
    }

    /**
     * @throws \RuntimeException
     */
    public function start()
    {
        // check if can start
        if (static::$running && static::$running !== $this) {
            throw new \RuntimeException('There is another Telltale instance running.');
        }
        if ($this->started) {
            throw new \RuntimeException('Telltale instance can not be started twice.');
        }

        // start agents
        static::$running = $this;
        $this->started = true;
        foreach ($this->agents as $agent) {
            $agent->start();
        }
    }

    /**
     * @throws \RuntimeException
     */
    public function stop()
    {
        // check if can stop
        if (!$this->started) {
            throw new \RuntimeException('Telltale instance was not started.');
        }
        if ($this->stopped) {
            throw new \RuntimeException('Telltale instance is already stopped.');
        }

        // stop agents
        $this->stopped = true;
        foreach ($this->agents as $agent) {
            $agent->stop();
        }

        // analyse
        foreach ($this->agents as $agent) {
            $report = $agent->analyse();
            $report->setContext($this->name);
            $report->spread();
        }

        static::$running = null;
    }

    /**
     * @param AgentInterface $agent
     * @return Telltable Provides a fluent interface
     */
    public function pushAgent(AgentInterface $agent)
    {
        array_unshift($this->agents, $agent);
        return $this;
    }

    /**
     * @throws \LogicException
     * @return AgentInterface
     */
    public function popAgent()
    {
        if (!$this->agents) {
            throw new \LogicException('You tried to pop from an empty agent stack.');
        }

        return array_shift($this->agents);
    }
}
