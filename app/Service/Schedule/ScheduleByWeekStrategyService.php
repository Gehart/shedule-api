<?php

namespace App\Service\Schedule;

use App\Service\Schedule\Exception\CannotFindDayException;
use App\Service\Schedule\Exception\CannotFindFirstGroupNameException;
use App\Service\Schedule\Processing\Dto\GroupCoordinatesDto;
use App\Service\Schedule\Processing\GroupCoordinatesProcessingService;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleByWeekStrategyService implements ScheduleProcessingInterface
{
    public const
        STRATEGY_NAME = 'by_week';

    public const FIRST_COLUMN_LETTER = 'A';

    public function __construct(
        private GroupCoordinatesProcessingService $groupProcessingService
    ) {
    }


    /**
     * @throws CannotFindFirstGroupNameException
     * @throws Exception
     */
    public function getSchedule(Spreadsheet $spreadsheet): mixed
    {
        $worksheet = $spreadsheet->getSheet(0);

        $groupsOnAWorksheet = $this->groupProcessingService->findAGroupsCoordinate($worksheet);
        $this->logGroupCoordinates($groupsOnAWorksheet);

        // определять подгруппы.

        // найти дни
        /*
         * column iterator
         * from now to end
         * process day by day
         * currend day range => day
         * until end
         * */
//        foreach ($groupsOnAWorksheet as $group) {
//
//        }

        $group = $groupsOnAWorksheet[0];
        [$columnOfGroup, $rowOfGroup] = Coordinate::coordinateFromString($group->getCoordinate());

        $rows = $worksheet->getRowIterator($rowOfGroup + 1, $worksheet->getHighestRow());

        // https://stackoverflow.com/questions/37027277/decrement-character-with-php
//        $columnIndexResult = chr(ord($columnIndexResult) - 1);

        foreach ($rows as $row) {
            $rowIndex = $row->getRowIndex();
            $dayName =  $this->findDayName($worksheet, $columnOfGroup, $rowIndex);
            $dayNameRange = $dayName['dayCellRange'];
        }

        return 'something';
    }

    /**
     * @param array<GroupCoordinatesDto> $groupsOnAWorksheet
     */
    private function logGroupCoordinates(array $groupsOnAWorksheet): void
    {
        if (!empty($groupsOnAWorksheet)) {
            $groupNames = [];
            foreach ($groupsOnAWorksheet as $group) {
                $groupNames[] = $group->getGroupName();
            }
            Log::info('Find groups on the worksheet', [
                'groupsCoordinates' => $groupNames,
                'count' => count($groupsOnAWorksheet),
            ]);
        } else {
            Log::warning('Can\'t process the group names');
        }
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

        $columnIterator = $worksheet->getColumnIterator(self::FIRST_COLUMN_LETTER, $columnOfGroup);
        $columnIterator->seek($columnOfGroup);

        while ($columnIterator->valid()) {
            $currentColumn = $columnIterator->current();
            $columnIndex = $currentColumn->getColumnIndex();
            $currentCoordinates = $columnIndex . $rowIndex;
            $currentCell = $worksheet->getCell($currentCoordinates);

            $cellValue = $this->getCellValueWithinRange($worksheet, $currentCell);

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

    /**
     * @param Worksheet $worksheet
     * @param Cell $cell
     * @return string
     */
    public function getCellValueWithinRange(Worksheet $worksheet, Cell $cell): string
    {
        $mergeRange = $cell->getMergeRange();

        if ($mergeRange) {
            $splitRange = Coordinate::splitRange($mergeRange);
            [$startCell] = $splitRange[0];
            $leftTopCell = $worksheet->getCell($startCell);
            $leftTopCellValue = $leftTopCell->getFormattedValue();
        } else {
            $leftTopCellValue = $cell->getFormattedValue();
        }

        return $leftTopCellValue;
    }
}
