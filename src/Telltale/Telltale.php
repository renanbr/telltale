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
use Telltale\Agent\MemoryPeakAgent;
use Telltale\Agent\CriticalPathAgent;
use Telltale\Agent\SlowestCallsAgent;
use Telltale\Agent\MemoryUsageCallsAgent;

class Telltale
{
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

    public function __construct()
    {
        $this->setupDefaultAgents();
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
        if ($this->started) {
            throw new \RuntimeException('Telltale can not be started twice.');
        }
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
        if (!$this->started) {
            throw new \RuntimeException('Telltale was not started.');
        }
        if ($this->stopped) {
            throw new \RuntimeException('Telltale is already stopped.');
        }
        $this->stopped = true;
        foreach ($this->agents as $agent) {
            $agent->stop();
        }
        foreach ($this->agents as $agent) {
            $agent->analyse();
        }
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

    protected function setupDefaultAgents()
    {
        $this->pushAgent(new MemoryUsageCallsAgent())
            ->pushAgent(new SlowestCallsAgent())
            ->pushAgent(new CriticalPathAgent())
            ->pushAgent(new MemoryPeakAgent());
    }
}
