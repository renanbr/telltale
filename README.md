# Telltale

__Statistics that help you improve application performance.__

_Telltale_ analyses current execution and provides information about possible bottlenecks in your application. Output reports are automatically sent to console of your browser, such as:

- Most time-consuming path;
- Most time-consuming calls;
- Most memory-consuming calls;
- Memory peak.

[![](https://github.com/renanbr/telltale/raw/master/docs/images/screenshot/overview.png)]

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
- [Composer](http://getcomposer.org)
- [Xdebug](http://xdebug.org/wizard.php) 2.1+
- [Firefox](https://addons.mozilla.org/en-US/firefox/addon/firebug/) and [Firebug](https://addons.mozilla.org/en-US/firefox/addon/firephp/)

### Contributing

Bugs and feature requests are tracked on [GitHub](https://github.com/renanbr/telltale/issues).

### Author

Renan de Lima - <renandelima@gmail.com>

### License

_Telltale_ is licensed under the [MIT License](http://opensource.org/licenses/MIT). See the [LICENSE](https://github.com/renanbr/telltale/blob/master/LICENSE) file for details.

### Acknowledgements

This library is inspired by [Derick Rethans](https://github.com/derickr)' [tracefile analyser script](http://derickrethans.nl/xdebug-and-tracing-memory-usage.html) and [ZendServer Code Tracing](https://www.zend.com/en/products/server/zend-server-code-tracing).