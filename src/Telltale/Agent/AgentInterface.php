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

interface AgentInterface
{
    /**
     * Start watching execution.
     */
    public function start();

    /**
     * Stop watching execution.
     *
     * Must be called before AgentInterface::analysis(). When working with
     * multiples agents, it reduces interference in subsequent calls analysis.
     */
    public function stop();

    /**
     * Performs analysis.
     *
     * Must be called after stop.
     */
    public function analyse();
}
