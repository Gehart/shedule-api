<?php

namespace App\Service\Schedule;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface ScheduleProcessingInterface
{
    public const STRATEGY_NAME = 'default';

    public function getSchedule(Spreadsheet $spreadsheet);
}
