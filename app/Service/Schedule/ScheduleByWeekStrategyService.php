<?php

namespace App\Service\Schedule;

use App\Entities\Day;
use App\Service\Schedule\Exception\CannotFindDayException;
use App\Service\Schedule\Exception\CannotFindFirstGroupNameException;
use App\Service\Schedule\Processing\DayName\DayGettingService;
use App\Service\Schedule\Processing\Dto\GroupCoordinatesDto;
use App\Service\Schedule\Processing\GroupCoordinatesProcessingService;
use App\Service\Schedule\Processing\Utils\ProcessingUtils;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ScheduleByWeekStrategyService implements ScheduleProcessingInterface
{
    public const
        STRATEGY_NAME = 'by_week';

    public const FIRST_COLUMN_LETTER = 'A';

    public function __construct(
        private GroupCoordinatesProcessingService $groupProcessingService,
        private ProcessingUtils $processingUtils,
        private DayGettingService $dayGettingService,
    ) {
    }


    /**
     * @throws CannotFindFirstGroupNameException
     * @throws Exception
     * @throws CannotFindDayException
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

        $group = $groupsOnAWorksheet[1];
        [$columnOfGroup, $rowOfGroup] = Coordinate::coordinateFromString($group->getCoordinate());

        $rows = $worksheet->getRowIterator($rowOfGroup + 1, $worksheet->getHighestRow());

        // https://stackoverflow.com/questions/37027277/decrement-character-with-php
//        $columnIndexResult = chr(ord($columnIndexResult) - 1);
        $currentDayDto = null;
        $processedDays = [];
        foreach ($rows as $row) {
            $rowIndex = $row->getRowIndex();

            if ($currentDayDto === null) {
                $dayDto =  $this->dayGettingService->findDay($worksheet, $columnOfGroup, $rowIndex);
                $currentDayDto = $dayDto;
                $processedDays[] = $dayDto;
            } else {
                $currentRowInDayRange = $this->currentRowInDayRange($currentDayDto->getCellRange(), $rowIndex);
                if (!$currentRowInDayRange) {
                    if ($currentDayDto->getDay()->getNumber() === Day::SATURDAY) {
                        Log::info('Finish to process group');
                        break;
                    }

                    $dayDto =  $this->dayGettingService->findDay($worksheet, $columnOfGroup, $rowIndex);
                    $currentDayDto = $dayDto;
                    $processedDays[] = $dayDto;
                }
            }


        }


        return 'something';
    }

    /**
     * @param string $dayRange
     * @param int $rowIndex
     * @return bool
     */
    private function currentRowInDayRange(string $dayRange, int $rowIndex): bool
    {
        [$startCoordinates, $endCoordinates] = Coordinate::getRangeBoundaries($dayRange);
        $lowRow = (int) $startCoordinates[1];
        $highRow = (int) $endCoordinates[1];
        return $rowIndex >= $lowRow && $rowIndex <= $highRow;
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
     * @return ProcessingUtils
     */
    public function getProcessingUtils(): ProcessingUtils
    {
        return $this->processingUtils;
    }
}
