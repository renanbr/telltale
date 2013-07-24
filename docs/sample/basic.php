<?php

use Telltale\Telltale;
use Telltale\Agent\MemoryPeakAgent;
use Telltale\Agent\CriticalPathAgent;
use Telltale\Agent\SlowestCallsAgent;
use Telltale\Agent\MemoryUsageCallsAgent;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('date.timezone', 'UTC');

// output buffer must be turned on
ob_start();

// autoload
require dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';

// setup
$telltale = new Telltale();
$telltale->pushAgent(new MemoryPeakAgent());
$telltale->pushAgent(new CriticalPathAgent());
$telltale->pushAgent(new SlowestCallsAgent());
$telltale->pushAgent(new MemoryUsageCallsAgent());
$telltale->start();

// ----- some functions

function ret_ord($c)
{
    return ord($c);
}

foreach (str_split('Telltale') as $char) {
    ret_ord($char);
}

// ----- recursive calls

function factorial($x)
{
    if (0 == $x) {
        return 1;
    }
    return $x * factorial($x - 1);
}

factorial(10);

// ----- high memory usage

function highMemory()
{
    str_repeat('elatlleT', rand(1111, 99999));
    str_repeat('Telltale', rand(1111, 99999));
}

highMemory();

// ----- slow execution

function foo($seconds)
{
    if ($seconds > 0) {
        sleep(rand($seconds - 1, $seconds + 1));
        bar($seconds - 1);
    }
}

function bar($seconds)
{
    $bar = str_repeat('telltale', rand(1111, 99999));
    if ($seconds > 0) {
        sleep(rand($seconds - 1, $seconds + 1));
        foo($seconds - 1);
    }
}

foo(4);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>Telltale - statistics that help you improve application performance</title>
        <style>
            * {font-family: "trebuchet ms",helvetica,sans-serif;}
            body {width: 700px; margin: 0 auto;}
            a, a:visited {text-decoration: none; color: #00f;}
            a:hover {text-decoration: underline;}
            h1 {font-size: 30pt; margin: 20px 0 0 0;}
            p, h1 {text-align: center;}
            p.links {font-size: 14pt; margin: 0}
            p.summary {font-size: 18pt;}
            pre {border-top: 1px solid #ddd; text-align: left; font-size: 9pt;}
            pre * {font-family: "DejaVu Sans Mono";}
        </style>
    </head>
    <body>
        <h1>Telltale</h1>
        <p class="links">
            <a href="https://github.com/renanbr/telltale#installation">installing</a> &middot;
            <a href="https://github.com/renanbr/telltale/issues">issues</a> &middot;
            <a href="https://github.com/renanbr/telltale/blob/master/LICENSE">license</a> &middot;
            <a href="https://github.com/renanbr/telltale">source</a>
        </p>
        <p class="summary">statistics that help you improve application performance</p>
        <pre><?php highlight_file(__FILE__) ?></pre>
    </body>
</html>