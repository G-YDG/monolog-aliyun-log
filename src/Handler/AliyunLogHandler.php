<?php

declare(strict_types=1);

namespace Ydg\MonologAliyunLog\Handler;

use Aliyun_Log_Client as Client;
use Aliyun_Log_Exception;
use Aliyun_Log_Models_LogItem as LogItem;
use Aliyun_Log_Models_PutLogsRequest as PutLogsRequest;
use Ydg\MonologAliyunLog\Formatter\AliyunLogFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Swoole\FastCGI\Record;
use TypeError;

class AliyunLogHandler extends AbstractProcessingHandler
{
    /**
     * @var Client
     */
    protected $client;

    protected $project;

    protected $logstore;

    public function __construct($client, $project, $logstore, $level = Logger::DEBUG, $bubble = true)
    {
        if (!$client instanceof Client) {
            throw new TypeError('Aliyun\Log\Client instance required');
        }

        $this->client = $client;
        $this->project = $project;
        $this->logstore = $logstore;

        parent::__construct($level, $bubble);
    }

    public function handle(array $record): bool
    {
        if (!$this->isHandling($record)) {
            return false;
        }

        if ($this->processors) {
            /** @var Record $record */
            $record = $this->processRecord($record);
        }

        $record['formatted'] = $this->getFormatter()->format($record);

        $this->write([$this->formatLogItem($record)]);

        return false === $this->bubble;
    }

    /**
     * @throws Aliyun_Log_Exception
     */
    protected function write(array $record): void
    {
        if (!empty($record)) {
            $this->client->putLogs(
                new PutLogsRequest($this->project, $this->logstore, null, null, $record)
            );
        }
    }

    protected function formatLogItem($record): LogItem
    {
        $logItem = new LogItem();

        $logItem->setTime(strtotime($record['formatted']['datetime']));
        $logItem->setContents($record['formatted']);
        return $logItem;
    }

    public function handleBatch(array $records): void
    {
        $logItems = [];

        foreach ($records as $record) {
            /**
             * @var array $record
             */
            if (!$this->isHandling($record)) {
                continue;
            }

            if ($this->processors) {
                /** @var Record $record */
                $record = $this->processRecord($record);
            }

            $record['formatted'] = $this->getFormatter()->format($record);

            $logItems[] = $this->formatLogItem($record);
        }

        $this->write($logItems);
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new AliyunLogFormatter(AliyunLogFormatter::SIMPLE_DATE);
    }
}