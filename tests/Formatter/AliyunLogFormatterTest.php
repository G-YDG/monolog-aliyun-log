<?php

declare(strict_types=1);

namespace YdgTest\MonologAliyunLog\Formatter;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Ydg\MonologAliyunLog\Formatter\AliyunLogFormatter;

class AliyunLogFormatterTest extends TestCase
{
    public function testSimpleFormat()
    {
        $record = [
            'message' => 'some log message',
            'context' => [],
            'level' => Logger::WARNING,
            'level_name' => Logger::getLevelName(Logger::WARNING),
            'channel' => 'test',
            'datetime' => new \DateTimeImmutable('2016-01-21T21:11:30.123456+00:00'),
            'extra' => [],
        ];

        $formatter = new AliyunLogFormatter();
        $formattedRecord = $formatter->format($record);

        $this->assertEquals(
            [
                'message' => 'some log message',
                'context' => '[]',
                'level' => 300,
                'level_name' => 'WARNING',
                'channel' => 'test',
                'datetime' => '2016-01-21T21:11:30+00:00',
                'extra' => '[]',
            ],
            $formattedRecord
        );
    }
}