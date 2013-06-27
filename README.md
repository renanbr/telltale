# Telltale

__Statistics to improve your application performance__

_Telltale_ analyses current execution and provide you information about possible bottlenecks in your application.
Output report is automatically sent to console of your browser.
Currently it works only with [FirePHP](https://addons.mozilla.org/en-US/firefox/addon/firephp/).

[![](https://github.com/renanbr/telltale/raw/master/docs/images/screenshot.png)]

## Installation


Add `renanbr/telltale` in your `composer.json` file (`require-dev` recommended). Run `composer update`. See more about [Composer](http://getcomposer.org/).

``` json
{
    "require-dev": {
        "renanbr/telltale": "dev-master"
    }
}
```

## Usage

Grabbing all code.

```php
<?php

// ignored code in statistics

$telltale = new \Telltale\Telltale();
$telltale->start();

// code analysed till the end of execution
```

Analysing a part of code.

```php
<?php

// ignored code in statistics

$telltale = new \Telltale\Telltale();
$telltale->start();

// code analysed

$telltale->stop();

// ignored code in statistics
```

## About

### Requirements

- PHP 5.3+
- [Xdebug](http://xdebug.org/) 2.1+
- [Firefox](https://addons.mozilla.org/en-US/firefox/addon/firebug/) with [Firebug](https://addons.mozilla.org/en-US/firefox/addon/firephp/) extension

### Contributing

Bugs and feature request are tracked on [GitHub](https://github.com/renanbr/telltale/issues)

### Author

Renan de Lima - <renandelima@gmail.com>

### License

Telltale is licensed under the MIT License - see the `LICENSE` file for details.

### Acknowledgements

This library is inspired by [Derick Rethans](https://github.com/derickr)' [tracefile analyser](http://derickrethans.nl/xdebug-and-tracing-memory-usage.html) script.
