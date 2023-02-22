<?php

declare(strict_types=1);

namespace Ydg\MonologAliyunLog\Formatter;

use Monolog\Formatter\NormalizerFormatter;

class AliyunLogFormatter extends NormalizerFormatter
{
    const SIMPLE_DATE = "Y-m-d\TH:i:sP";

    public function format(array $record)
    {
        $record = parent::format($record);

        foreach ($record as &$value) {
            $value = $this->formatString($value);
        }

        return $record;
    }

    public function formatString($value)
    {
        if (is_array($value)) {
            return $this->toJson($value);
        }

        return $value;
    }
}