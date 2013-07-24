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
use Monolog\Handler\ChromePHPHandler;
use Telltale\Util\Monolog\Formatter\ChromePhpTableFormatter;

class TableReport implements ReportInterface
{
    /**
     * @var string
     */
    protected $context;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $rows = array();

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
        $context = array('telltale-table' => $this->rows);
        $logger->info($this->title, $context);
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

        $firePhp = new FirePhpHandler();
        $firePhp->setFormatter(new WildfireTableFormatter());
        $logger->pushHandler($firePhp);

        $chromePhp = new ChromePHPHandler();
        $chromePhp->setFormatter(new ChromePhpTableFormatter());
        $logger->pushHandler($chromePhp);

        return $logger;
    }
}
