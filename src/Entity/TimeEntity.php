<?php
namespace ClockifyToFormat\Entity;
use Carbon\Carbon;
use http\Exception\RuntimeException;

class TimeEntity
{
    private const COLUMN_COUNT = 17;
    public string $project = '';
    public string $client = '';
    public string $description = '';
    public string $task = '';
    public string $user = '';
    public string $group = '';
    public string $email = '';
    public string $tags = '';
    public string $billable = '';
    public ?Carbon $startDateTime = null;
    public ?Carbon $endDateTime = null;
    public ?TimeDuration $duration = null;
    public string $billableRate = '';
    public string $billableAmount = '';
    public static function fromDetailedExport(array $export): TimeEntity
    {
        if(count($export) < self::COLUMN_COUNT){
            Throw new RuntimeException('This CSV contains the incorrect amount of columns! Expected ' . self::COLUMN_COUNT .' but found ' . count($export));
        }
        $time = new self();

        $time->project = $export[0];
        $time->client = $export[1];
        $time->description = $export[2];
        $time->task = $export[3];
        $time->user = $export[4];
        $time->group = $export[5];
        $time->email = $export[6];
        $time->tags = $export[7];
        $time->billable = $export[8];
        $time->startDateTime = Carbon::parse($export[9] . ' ' . $export[10]);
        $time->endDateTime = Carbon::parse($export[11] . ' ' . $export[12]);
        $time->duration = TimeDuration::fromCarbon($time->startDateTime, $time->endDateTime);
        $time->billableRate = $export[15];
        $time->billableAmount = $export[16];

        return $time;
    }
}