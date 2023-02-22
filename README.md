# Monolog Aliyu Log
## Installation

Install the latest version with

```bash
$ composer require ydg/monolog-aliyun-log
```

## Basic Usage

```php
<?php

use Monolog\Logger;
use Hdk\MonologAliyunLog\AliyunLogHelper;

$config = [
    'endpoint' => 'your endpoint',
    'accessKeyId' => 'your accessKeyId',
    'accessKey' => 'your accessKey',
    'project' => 'your project',
    'logstore' => 'your logstore',
];

$log = new Logger('name');
$log->pushHandler(AliyunLogHelper::getLogHandler(AliyunLogHelper::getLogClient($config), $config));

// add records to the log
$log->warning('Foo');
$log->error('Bar');
```