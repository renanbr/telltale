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

class TextReport implements ReportInterface
{
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
        $logger = new Logger('Telltale');
        $logger->pushHandler(new FirePhpHandler());
        $logger->pushHandler(new ChromePHPHandler());
        return $logger;
    }
}
