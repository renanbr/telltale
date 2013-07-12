<?php

/*
 * This file is part of the Telltale package.
 *
 * (c) Renan de Lima <renandelima@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TelltaleTest\Util\Monolog\Formatter;

/**
 * @cover Telltale\Util\Monolog\Handler\FirePhpHandler
 */
class FirePhpHandlerTest extends \PHPUnit_Framework_TestCase
{

    public function testMessageIndex()
    {
        $handler = $this->getMock(
            'Telltale\\Util\\Monolog\\Handler\\FirePhpHandler',
            array('createHeader')
        );

        $reflection = new \ReflectionClass($handler);
        $indexAttr = $reflection->getProperty('messageIndex');
        $indexAttr->setAccessible(true);
        $index = $indexAttr->getValue($handler);
        $method = $reflection->getMethod('createRecordHeader');
        $method->setAccessible(true);

        $handler
            ->expects($this->once())
            ->method('createHeader')
            ->with(
                $this->identicalTo(array(1, 1, 1, $index)),
                $this->identicalTo('formatted text')
            );

        $method->invoke($handler, array('formatted' => 'formatted text'));
    }
}
