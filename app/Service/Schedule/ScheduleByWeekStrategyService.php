<?php

namespace App\Service\Schedule;

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
            $dayName =  $this->dayGettingService->findDayName($worksheet, $columnOfGroup, $rowIndex);
//            $dayNameRange = $dayName['dayCellRange'];
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
     * @return ProcessingUtils
     */
    public function getProcessingUtils(): ProcessingUtils
    {
        return $this->processingUtils;
    }
}
