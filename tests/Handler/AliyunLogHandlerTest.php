<?php

declare(strict_types=1);

namespace YdgTest\MonologAliyunLog\Handler;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Ydg\MonologAliyunLog\AliyunLogHelper;

class AliyunLogHandlerTest extends TestCase
{
    public function testHandle()
    {
        $config = [
            'name' => env('ALI_LOG_NAME', 'aliyun'),
            'endpoint' => env('ALI_LOG_ENDPOINT'),
            'accessKeyId' => env('ALI_LOG_ACCESS_KEY_ID'),
            'accessKey' => env('ALI_LOG_ACCESS_KEY'),
            'project' => env('ALI_LOG_PROJECT'),
            'logstore' => env('ALI_LOG_LOGSTORE'),
            'token' => env('ALI_LOG_TOKEN', ''),
        ];

        $logger = AliyunLogHelper::instance()->getLogger($config);

        $this->assertTrue(
            $logger->addRecord(Logger::WARNING, 'test')
        );
    }
}