<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Telltale\Util\Monolog\Handler;

use Monolog\Handler\FirePHPHandler as MonologFirePHPHandler;

class FirePhpHandler extends MonologFirePHPHandler
{
    /**
     * @var integer
     */
    protected static $messageIndex = 1000;

    /**
     * {@inheritdoc}
     */
    protected function createRecordHeader(array $record)
    {
        return $this->createHeader(
            array(1, 1, 1, static::$messageIndex++),
            $record['formatted']
        );
    }
}
