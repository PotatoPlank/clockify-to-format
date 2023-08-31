<?php
namespace ClockifyToFormat\Clockify;
class TimeEntity
{
    private string $project = '';
    private string $client = '';
    private string $description = '';
    private string $task = '';
    private string $user = '';
    private string $group = '';
    private string $email = '';
    private string $tags = '';
    private string $billable = '';
    private string $startDate = '';
    private string $startTime = '';
    private string $endDate = '';
    private string $endTime = '';
    private string $duration = '';
    private string $durationDecimal = '';
    private string $billableRate = '';
    private string $billableAmount = '';
    public static function fromCsvRow(): TimeEntity
    {
        $time = new self();



        return new self();
    }
}