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

use Monolog\Formatter\WildfireFormatter;

class WildfireTableFormatter extends WildfireFormatter
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

        $record = $this->normalize($record);

        $type = 'TABLE';
        $file = isset($record['extra']['file']) ? $record['extra']['file'] : '';
        $line = isset($record['extra']['line']) ? $record['extra']['line'] : '';
        $label = $record['channel'] . ': ' . $record['message'];
        $message = $record['context']['telltale-table'];
        $handleError = false;

        $json = $this->toJson(
            array(
                array(
                    'Type'  => $type,
                    'File'  => $file,
                    'Line'  => $line,
                    'Label' => $label,
                ),
                $message,
            ),
            $handleError
        );

        return sprintf('%s|%s|', strlen($json), $json);
    }
}
