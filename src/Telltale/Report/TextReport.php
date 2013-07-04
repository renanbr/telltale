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
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\ChromePHPHandler;

class TextReport implements ReportInterface
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var \Monolog\Logger
     */
    protected static $logger;

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
        if (!static::$logger) {
            $logger = new Logger('Telltale');
            $logger->pushHandler(new FirePHPHandler());
            $logger->pushHandler(new ChromePHPHandler());
            static::$logger = $logger;
        }

        static::$logger->info($this->text);
    }
}
