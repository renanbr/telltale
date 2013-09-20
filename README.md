Telltale
========

[![Build Status](https://secure.travis-ci.org/renanbr/telltale.png)](http://travis-ci.org/renanbr/telltale)
[![Dependency Status](https://www.versioneye.com/user/projects/51efce5a632bac469902904d/badge.png)](https://www.versioneye.com/user/projects/51efce5a632bac469902904d)

__Statistics that help you improve application performance.__

_Telltale_ analyses current execution and provides information about potential
bottlenecks in your application. Analyzes are executed by _agents_. Output
reports are automatically sent to console of your browser. Available _agents_:

- Memory Peak;
- Critical Path;
- Slowest Calls;
- Memory Usage Calls.

Usage
-----

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

Then your browser displays informations like this...

![Firebug Sample](https://raw.github.com/renanbr/telltale/master/docs/images/screenshot/overview.png)
<p>

Analysing a code snippet...

```php
<?php
// ignored
$telltale->start();
// analysed
$telltale->stop();
// ignored
```

Installation
------------

Add `renanbr/telltale` dependency using Composer.

```sh
php composer.phar require renanbr/telltale:~1
```

Or change `composer.json` file...

```json
{
    "require": {
        "renanbr/telltale": "~1"
    }
}
```

For Composer documentation, please refer to [getcomposer.org](http://getcomposer.org/).

Environment Requirements
------------------------

- [PHP](http://php.net) 5.3+
- [Xdebug](http://xdebug.org/docs/install) 2.1+
- [FirePHP](https://addons.mozilla.org/en-US/firefox/addon/firephp/) for [Firefox](http://www.mozilla.org/en-US/firefox/new/), or
  - [ChromeLogger](https://chrome.google.com/webstore/detail/chrome-logger/noaneddfkdjfnfdakjjmocngnfkfehhd) for [Chrome](https://google.com/chrome)

Acknowledgements
----------------

This library is inspired by [Derick Rethans](https://github.com/derickr)'
[tracefile analyser script](http://derickrethans.nl/xdebug-and-tracing-memory-usage.html)
and [ZendServer Code Tracing](https://www.zend.com/en/products/server/zend-server-code-tracing).
