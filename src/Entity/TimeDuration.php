<?php

namespace ClockifyToFormat\Entity;

use Carbon\Carbon;

class TimeDuration
{
    public string $decimalHours = '0';
    public string $hours = '0';
    public static function fromCarbon(Carbon $begin, Carbon $end): TimeDuration
    {
        $duration = new self();

        $hours = $begin->diffInRealHours($end);
        $minutes = $begin->diffInRealMinutes($end) % 60;
        if($minutes > 0 && $minutes < 30) {
            $minutes = 30;
        }else if($minutes > 30) {
            $minutes = 0;
            $hours++;
        }
        $duration->decimalHours = $hours . '.' . ($minutes === 30 ? '5' : '0');
        $duration->hours = $hours . ':' . ($minutes === 30 ? '30' : '00') . ':00';

        return $duration;
    }
}