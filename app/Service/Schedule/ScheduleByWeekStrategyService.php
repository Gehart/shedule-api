<?php

namespace App\Service\Schedule;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ScheduleByWeekStrategyService implements ScheduleProcessingInterface
{
    public const STRATEGY_NAME = 'by_week';

    public function getSchedule(Spreadsheet $spreadsheet)
    {
        $sheetCount = $spreadsheet->getSheetCount();
        $worksheets = $spreadsheet->getSheet($sheetCount - 1);
        foreach ($worksheets as $worksheet) {
            $mergeCells = $worksheet->getMergeCells();
        }
    }
}
