<?php

namespace ClockifyToFormat\Clockify;

use ClockifyToFormat\Entity\TimeEntity;

class DetailedExport
{
    private array $times = [];
    private string $user = '';
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
            $entity = TimeEntity::fromDetailedExport($cells);
            if(empty($export->user))
            {
                $export->user = $entity->user;
            }
            $export->times[] =  $entity;
        }
        fclose($csv);

        return $export;
    }

    /**
     * @return array<TimeEntity>
     */
    public function getTimes(): array
    {
        return $this->times;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }
}