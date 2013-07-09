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
use Telltale\Util\Monolog\Formatter\WildfireTableFormatter;

class TableReport implements ReportInterface
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $rows = array();

    /**
     * @var \Monolog\Logger
     */
    protected static $firePhpLogger;

    /**
     * @param string $title
     * @return TableReport Provides a fluent interface
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param array $row
     * @return TableReport Provides a fluent interface
     */
    public function addRow(array $row)
    {
        $this->rows[] = $row;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function spread()
    {
        if (!$this->title || !$this->rows) {
            return;
        }
        $this->spreadFirePhp();
    }

    protected function spreadFirePhp()
    {
        if (!static::$firePhpLogger) {
            $logger = new Logger('Telltale');

            $firePhpHandler = new FirePhpHandler();
            $firePhpHandler->setFormatter(new WildfireTableFormatter());
            $logger->pushHandler($firePhpHandler);

            static::$firePhpLogger = $logger;
        }

        $context = array('wildfire-table' => $this->rows);
        static::$firePhpLogger->info($this->title, $context);
    }
}
