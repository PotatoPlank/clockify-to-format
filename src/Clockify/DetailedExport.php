<?php

namespace ClockifyToFormat\Clockify;

use ClockifyToFormat\Entity\TimeEntity;

class DetailedExport
{
    private array $times = [];
    public static function fromCsv($path): DetailedExport
    {
        $export = new self();

        $csv = fopen($path, 'rb+');
        $counter = 0;
        while ($cells = fgetcsv($csv)) {
            if($counter === 0)
            {
                $counter++;
                continue;
            }
            $export->times[] =  TimeEntity::fromDetailedExport($cells);
        }
        fclose($csv);

        return $export;
    }

    /**
     * @return array
     */
    public function getTimes(): array
    {
        return $this->times;
    }
}