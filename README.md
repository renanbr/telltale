# Telltale

__Statistics that help you improve application performance.__

_Telltale_ analyses current execution and provides information about potential
bottlenecks in your application. Analyzes are executed by _agents_. Output
reports are automatically sent to console of your browser. Default _agents_:

- __MemoryPeakAgent__: top memory usage;
- __CriticalPathAgent__: shows the most time-consuming path;
- __SlowestCallsCallsAgent__: shows slowest calls;
- __MemoryUsageCallsAgent__: shows top memory usage calls.

<p align="center">
![](https://raw.github.com/renanbr/telltale/master/docs/images/screenshot/overview.png)
<p>

## Usage

```php
<?php

use Telltale\Telltale;
use Telltale\Agent\MemoryPeakAgent;
use Telltale\Agent\CriticalPathAgent;
use Telltale\Agent\SlowestCallsAgent;
use Telltale\Agent\MemoryUsageCallsAgent;

// create an analyser
$telltale = new Telltale();
$telltale->pushAgent(new MemoryPeakAgent());
$telltale->pushAgent(new CriticalPathAgent());
$telltale->pushAgent(new SlowestCallsAgent());
$telltale->pushAgent(new MemoryUsageCallsAgent());

// start watching
$telltale->start();

```

Analysing a part of code:

```php
<?php
// ignored
$telltale->start();
// analysed
$telltale->stop();
// ignored
```

## Installation

Installation of this module uses [Composer](http://getcomposer.org/).

```sh
php composer.phar require renanbr/telltale:dev-master
```
or add `renanbr/telltale` to `composer.json` manually
``` json
{
    "require": {
        "renanbr/telltale": "dev-master"
    }
}
```

## About

### Requirements

- [PHP](http://php.net) 5.3+
- [Xdebug](http://xdebug.org/docs/install) 2.1+
- [Firefox](https://addons.mozilla.org/en-US/firefox/addon/firebug/) and [Firebug](https://addons.mozilla.org/en-US/firefox/addon/firephp/)
- FirePHP

### Contributing

Bugs and feature requests are tracked on [GitHub](https://github.com/renanbr/telltale/issues).

### Author

Renan de Lima - <renandelima@gmail.com>

### License

_Telltale_ is licensed under the [MIT License](http://opensource.org/licenses/MIT).
See the [LICENSE](https://github.com/renanbr/telltale/blob/master/LICENSE) file
for details.

### Acknowledgements

This library is inspired by [Derick Rethans](https://github.com/derickr)'
[tracefile analyser script](http://derickrethans.nl/xdebug-and-tracing-memory-usage.html)
and [ZendServer Code Tracing](https://www.zend.com/en/products/server/zend-server-code-tracing).
