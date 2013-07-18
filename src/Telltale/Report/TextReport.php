<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Telltale\Report;

use Monolog\Logger;
use Telltale\Util\Monolog\Handler\FirePhpHandler;
use Monolog\Handler\ChromePHPHandler;
use Telltale\Context\ContextInterface;

class TextReport implements ReportInterface
{
    /**
     * @var ContextInterface
     */
    protected $context;
    /**
     * @var string
     */
    protected $text;

    /**
     * @param string $text
     * @return TextReport Provides a fluent interface
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function spread()
    {
        $logger = $this->createLogger();
        $logger->info($this->text);
    }

    /**
     * @return Logger
     */
    protected function createLogger()
    {
        $name = 'Telltale';
        if ($this->context) {
            $name .= ' [' . $this->context . ']';
        }
        $logger = new Logger($name);
        $logger->pushHandler(new FirePhpHandler());
        $logger->pushHandler(new ChromePHPHandler());
        return $logger;
    }
}
