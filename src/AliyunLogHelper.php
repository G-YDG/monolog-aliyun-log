<?php

namespace Ydg\MonologAliyunLog;

use Aliyun_Log_Client as Client;
use Monolog\Logger;
use Ydg\MonologAliyunLog\Handler\AliyunLogHandler;

class AliyunLogHelper
{
    private static $instance;
    private $logger;

    static function instance(...$args)
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }

    public function getLogger(array $config): Logger
    {
        if (!$this->logger instanceof Logger) {
            $this->logger = new Logger($config['name']);
        }
        $this->logger->pushHandler(AliyunLogHelper::getLogHandler(AliyunLogHelper::getLogClient($config), $config));

        return $this->logger;
    }

    public static function getLogHandler($client, $config): AliyunLogHandler
    {
        return new AliyunLogHandler($client, $config['project'], $config['logstore']);
    }

    public static function getLogClient($config): Client
    {
        return new Client($config['endpoint'], $config['accessKeyId'], $config['accessKey'], $config['token'] ?? "");
    }
}