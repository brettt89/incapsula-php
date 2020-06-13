<?php

namespace Incapsula\API\Parameters;

class TimeRange implements Parameter
{
    const TODAY='today';
    const LAST_7_DAYS='last_7_days';
    const LAST_30_DAYS='last_30_days';
    const LAST_90_DAYS='last_90_days';
    const MONTH_TO_DATE='month_to_date';
    const CUSTOM='custom';
    
    private $timeRange;
    private $start;
    private $end;

    public function __construct(string $timeRange = self::TODAY, string $start = null, string $end = null)
    {
        if (!isset($timeRange)) {
            throw new \InvalidArgumentException("Time Range must be provided.");
        }
        
        switch ($timeRange) {
            case self::TODAY:
            case self::LAST_7_DAYS:
            case self::LAST_30_DAYS:
            case self::LAST_90_DAYS:
            case self::MONTH_TO_DATE:
                $this->timeRange = $timeRange;
                break;
            case self::CUSTOM:
                $this->timeRange = $timeRange;
                if (!isset($start) || !isset($end)) {
                    throw new \InvalidArgumentException("Time Range 'custom' requires Start and End times to be provided.");
                }

                if (!$this->isValidTimeStamp($start) || !$this->isValidTimeStamp($end)) {
                    throw new \InvalidArgumentException("All dates should be specified as number of milliseconds since midnight 1970 (UNIX time * 1000)");
                }
                $this->start = $start;
                $this->end = $end;
                break;
            default:
                throw new \InvalidArgumentException('Invald Time Range provided.');
        }
    }
    
    public function getRequestParameters(): array
    {
        $parameters = [
            'time_range' => $this->timeRange
        ];
        if (isset($this->start) && isset($this->end)) {
            $parameters['start'] = $this->start;
            $parameters['end'] = $this->end;
        }
        
        return $parameters;
    }

    public function isValidTimeStamp($timestamp): bool
    {
        return ((string) (int) $timestamp === $timestamp);
    }
}
