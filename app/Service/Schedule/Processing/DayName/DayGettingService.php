<?php

namespace App\Service\Schedule\Processing\DayName;

use App\Entities\Day;
use App\Service\Schedule\Dictionary\ScheduleDictionary;
use App\Service\Schedule\Exception\CannotFindDayException;
use App\Service\Schedule\Processing\DayName\Assembler\DayAssembler;
use App\Service\Schedule\Processing\DayName\Dto\DayCellDto;
use App\Service\Schedule\Processing\Utils\ProcessingUtils;
use App\Service\Schedule\ScheduleByWeekStrategyService;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DayGettingService
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private array $dayNameData = [];

    public function __construct(
        private ProcessingUtils $utils,
        private ScheduleDictionary $scheduleDictionary,
        private DayAssembler $dayAssembler,
    ) {
    }

    /**
     * @param Worksheet $worksheet
     * @param string $columnOfGroup
     * @param int $rowIndex
     * @return DayCellDto
     * @throws Exception
     * @throws CannotFindDayException
     */
    public function findDay(Worksheet $worksheet, string $columnOfGroup, int $rowIndex): DayCellDto
    {
        $dayNameCell = null;
        $currentCoordinates = null;
        $day = null;

        $columnIterator = $worksheet->getColumnIterator(ScheduleByWeekStrategyService::FIRST_COLUMN_LETTER, $columnOfGroup);
        $columnIterator->seek($columnOfGroup);

        while ($columnIterator->valid()) {
            $currentColumn = $columnIterator->current();
            $columnIndex = $currentColumn->getColumnIndex();
            $currentCoordinates = $columnIndex . $rowIndex;
            $currentCell = $worksheet->getCell($currentCoordinates);

            $cellValue = $this->utils->getCellValueWithinRange($worksheet, $currentCell);

            $day = $this->getDayByCellValue($cellValue);
            if ($day !== null) {
                Log::info('Process a day between coordinates', [
                    'day_name' => $cellValue,
                    'current coordinate' => $currentCoordinates,
                ]);
                $dayNameCell = $currentCell;
                break;
            }
            $columnIterator->prev();
        }

        if ($day) {
            $dayNameRange = $dayNameCell->getMergeRange();

            if (!$dayNameRange) {
                Log::warning('Look like the day name cell is incorrect!');
            }

            return new DayCellDto($day, $dayNameRange);
        } else {
            throw new CannotFindDayException($currentCoordinates);
        }
    }

    /**
     * @param string $cellValue
     * @return Day|null
     */
    private function getDayByCellValue(string $cellValue): ?Day
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
            return $this->dayAssembler->create($dayNumber);
        } else {
            return null;
        }
    }
}
