<?php

namespace App\Service\Schedule\Processing\DayName;

use App\Service\Schedule\Dictionary\ScheduleDictionary;
use App\Service\Schedule\Exception\CannotFindDayException;
use App\Service\Schedule\Processing\Utils\ProcessingUtils;
use App\Service\Schedule\ScheduleByWeekStrategyService;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DayGettingService
{
    public function __construct(
        private ProcessingUtils $utils,
        private ScheduleDictionary $scheduleDictionary,
    ) {
    }

    /**
     * @param Worksheet $worksheet
     * @param string $columnOfGroup
     * @param int $rowIndex
     * @return array|null
     * @throws Exception
     * @throws CannotFindDayException
     */
    public function findDayName(Worksheet $worksheet, string $columnOfGroup, int $rowIndex): ?array
    {
        $dayNameCell = null;

        $columnIterator = $worksheet->getColumnIterator(ScheduleByWeekStrategyService::FIRST_COLUMN_LETTER, $columnOfGroup);
        $columnIterator->seek($columnOfGroup);

        while ($columnIterator->valid()) {
            $currentColumn = $columnIterator->current();
            $columnIndex = $currentColumn->getColumnIndex();
            $currentCoordinates = $columnIndex . $rowIndex;
            $currentCell = $worksheet->getCell($currentCoordinates);

            $cellValue = $this->utils->getCellValueWithinRange($worksheet, $currentCell);


//           /*
//  Если в словаре, то сохранить (пройти по всем словарям и все в таком духе)
//
//
//*/
            if (mb_strtolower($cellValue) === mb_strtolower('Понедельник')) {
                Log::info('Cool!', [
                    'day_name' => $cellValue,
                    'current coordinate' => $currentCoordinates,
                ]);
                $dayNameCell = $currentCell;
                break;
            }
            $columnIterator->prev();
        }

        if ($dayNameCell) {
            $dayNameRange = $dayNameCell->getMergeRange();

            if (!$dayNameRange) {
                Log::warning('Look like the day name cell is incorrect!');
            }

            return [
                'dayName' => $dayNameCell->getFormattedValue(),
                'dayCellRange' => $dayNameRange,
            ];
        } else {
            throw new CannotFindDayException();
        }
    }
}
