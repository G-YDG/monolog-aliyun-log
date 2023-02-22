<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter;
use Dotenv\Repository\RepositoryBuilder;

!defined('BASE_PATH') && define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/vendor/autoload.php';

if (file_exists(BASE_PATH . '/.env')) {
    $repository = RepositoryBuilder::createWithNoAdapters()
        ->addAdapter(Adapter\PutenvAdapter::class)
        ->immutable()
        ->make();

    Dotenv::create($repository, [BASE_PATH])->load();
}
