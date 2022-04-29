<?php

namespace App\Service\Schedule\Processing\DayName;

use App\Service\Schedule\Dictionary\ScheduleDictionary;
use App\Service\Schedule\Exception\CannotFindDayException;
use App\Service\Schedule\Processing\Utils\ProcessingUtils;
use App\Service\Schedule\ScheduleByWeekStrategyService;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DayGettingService
{
    private array $dayNameData = [];

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
        $currentCoordinates = null;

        $columnIterator = $worksheet->getColumnIterator(ScheduleByWeekStrategyService::FIRST_COLUMN_LETTER, $columnOfGroup);
        $columnIterator->seek($columnOfGroup);

        while ($columnIterator->valid()) {
            $currentColumn = $columnIterator->current();
            $columnIndex = $currentColumn->getColumnIndex();
            $currentCoordinates = $columnIndex . $rowIndex;
            $currentCell = $worksheet->getCell($currentCoordinates);

            $cellValue = $this->utils->getCellValueWithinRange($worksheet, $currentCell);

            $day= $this->getDayByCellValue($cellValue);
            if ($day !== null) {
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
            throw new CannotFindDayException($currentCoordinates);
        }
    }

    /**
     * @param string $cellValue
     * @return array|null
     */
    private function getDayByCellValue(string $cellValue): ?array
    {
        if (!$this->dayNameData) {
            $this->dayNameData = $this->scheduleDictionary->getDayNameData();
        }
        $dayNumber = null;

        foreach ($this->dayNameData as $dayKey => $dayData) {
            foreach ($dayData['names'] as $dayName) {
                if (mb_strtolower($cellValue) === mb_strtolower($dayName)) {
                    $dayNumber = $dayData['numberOfDay'];
                    break;
                }
            }
        }

        if ($dayNumber !== null) {
            return [
                'number' => $dayNumber,
            ];
        } else {
            return null;
        }
    }
}
