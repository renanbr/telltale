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

/**
 * Provides trace file in Xdebug computerized format to be used into analyse().
 */
abstract class AbstractTraceAgent extends AbstractAgent
{
    /**
     * @var TraceManager
     */
    protected $traceManager;

    /**
     * @var string
     */
    protected $traceFile;

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        parent::start();
        $this->traceManager = $this->createTraceManager();
        $this->traceFile = $this->traceManager->start();
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        parent::stop();
        $this->traceManager->stop();
    }

    /**
     * @return TraceManager
     */
    protected function createTraceManager()
    {
        return new TraceManager();
    }
}
