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

### Grabbing all code

```php
<?php

// ignored execution in statistics

$telltale = new \Telltale\Telltale();
$telltale->start();

// analysed
```

### Analysing a part of code

```php
<?php

// ignored execution in statistics

$telltale = new \Telltale\Telltale();
$telltale->start();

// analysed

$telltale->stop();

// ignored execution in statistics
```

## Installation


Add `renanbr/telltale` to [`composer.json`](http://getcomposer.org/).

``` json
{
    "require-dev": {
        "renanbr/telltale": "dev-master"
    }
}
```

## About

### Requirements

- [PHP](http://php.net) 5.3+
- [Xdebug](http://xdebug.org/docs/install) 2.1+
- [Firefox](https://addons.mozilla.org/en-US/firefox/addon/firebug/) and [Firebug](https://addons.mozilla.org/en-US/firefox/addon/firephp/)

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
