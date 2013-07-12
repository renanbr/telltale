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

/**
 * Provides synchronization among start(), stop() and analyse() methods.
 */
abstract class AbstractAgent implements AgentInterface
{
    /**
     * @var boolean
     */
    protected $started = false;

    /**
     * @var boolean
     */
    protected $stopped = false;

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        if ($this->started) {
            throw new \RuntimeException('Telltale Agent can not be started twice.');
        }
        $this->started = true;
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        if (!$this->started) {
            throw new \RuntimeException('Can not stop Telltale Agent, it was not started.');
        }
        if ($this->stopped) {
            throw new \RuntimeException('Telltale Agent is already stopped.');
        }
        $this->stopped = true;
    }

    /**
     * {@inheritdoc}
     */
    public function analyse()
    {
        if (!$this->started) {
            throw new \RuntimeException('Telltale Agent can not analyse, it was not started.');
        }
        if (!$this->stopped) {
            $this->stop();
        }
    }
}
