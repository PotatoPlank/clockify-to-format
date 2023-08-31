<?php

namespace ClockifyToFormat\Export;

use Carbon\Carbon;
use ClockifyToFormat\Clockify\DetailedExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DefaultExcel
{
    private array $headers = [
        'Date:',
        'Task:',
        'Staff:',
        'Hours:',
        'Client:',
    ];
    private Spreadsheet $spreadsheet;
    public function __construct() {
        $this->spreadsheet = new Spreadsheet();

        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        foreach($this->headers as $i => $header)
        {
            $borderColor = new Color('ffa2b8e1');
            $fontColor = new Color('ff44546a');
            $sheet->setCellValue([$i + 1, 1], $header);
            $head = $sheet->getCell([$i + 1, 1]);

            $style = $sheet->getStyle($head->getColumn() . $head->getRow());

            $style->getBorders()
                ->getBottom()
                ->setColor($borderColor)
                ->setBorderStyle(Border::BORDER_THICK);
            $style->getFont()
                ->setSize(13)
                ->setBold(true)
                ->setColor($fontColor)
                ->setName('Calibri');
            $style->getAlignment()
                ->setVertical(Alignment::VERTICAL_BOTTOM);
        }
    }

    public static function fromDetailedExport(DetailedExport $detailedExport): DefaultExcel
    {
        $export = new self();

        $days = [];
        foreach ($detailedExport->getTimes() as $time) {
            $groupDate = $time->startDateTime->format('Y-m-d');

            $formattedClient = $time->client;
            if(empty($formattedClient)){
                $formattedClient = $time->project;
            }else if(!empty($time->project)){
                $formattedClient .= " ({$time->project})";
            }

            if(!isset($days[$groupDate][$formattedClient]))
            {
                $days[$groupDate][$formattedClient]['hours'] = 0;
                $days[$groupDate][$formattedClient]['tasks'] = [];
            }

            $days[$groupDate][$formattedClient]['hours'] += (float)$time->duration->decimalHours;
            $days[$groupDate][$formattedClient]['tasks'][] = $time->description;
        }
        ksort($days);

        $sheet = $export->spreadsheet->getActiveSheet();
        $row = 2;

        foreach($days as $date => $day)
        {
            $date = Carbon::parse($date)->format('m/d/Y');

            foreach($day as $clientName => $entry)
            {
                $task = implode(', ', $entry['tasks']);

                $sheet->setCellValue([1, $row], $date);
                $sheet->setCellValue([2, $row], $task);
                $sheet->setCellValue([3, $row], $detailedExport->getUser());
                $sheet->setCellValue([4, $row], $entry['hours']);
                $sheet->setCellValue([5, $row], $clientName);

                $columnCount = count($export->headers);
                for ($i=0; $i < $columnCount; $i++) {
                    $sheet->getCell([$i + 1, $row])->getStyle()->getFont()
                        ->setName('Calibri')->setSize(12);
                }
                $row++;
            }
        }


        return $export;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function toString(): string
    {
        ob_start();
        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');

        return ob_get_clean();
    }

    /**
     * @param string $filename
     * @return void
     * @throws Exception
     */
    public function saveToFile(string $filename): void
    {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filename);
    }
}