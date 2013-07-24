<?php

/*
 * This file is part of the Telltale package.
*
* (c) Renan de Lima <renandelima@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Telltale\Util\Monolog\Formatter;

use Monolog\Formatter\ChromePHPFormatter;

class ChromePhpTableFormatter extends ChromePHPFormatter
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        if (!isset($record['context']['telltale-table']) ||
            !is_array($record['context']['telltale-table'])
        ) {
            return parent::format($record);
        }

        $table = $record['context']['telltale-table'];
        $tableHeader = array_fill(0, count(reset($table)), null);
        $tableHeader[0] = $record['channel'] . ': ' . $record['message'];
        array_unshift($table, $tableHeader);

        return array(
            null,
            $table,
            'unknown',
            'table',
        );
    }
}
