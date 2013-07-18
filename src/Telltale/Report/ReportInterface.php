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

interface ReportInterface
{
    /**
     * @param string $context
     */
    public function setContext($context);

    /**
     * Send report to endpoint.
     */
    public function spread();
}
