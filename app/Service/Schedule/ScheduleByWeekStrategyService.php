<?php

namespace App\Service\Schedule;

use App\Service\Schedule\Exception\CannotFindFirstGroupNameException;
use App\Service\Schedule\Processing\GroupCoordinatesProcessingService;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleByWeekStrategyService implements ScheduleProcessingInterface
{
    public const
        STRATEGY_NAME = 'by_week';

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
        $worksheet = $spreadsheet->getSheet(1);

        $groupsOnAWorksheet = $this->groupProcessingService->findAGroupsCoordinate($worksheet);

        if (!empty($groupsOnAWorksheet)) {
            Log::info('Find groups on the worksheet', [
                'groupsCoordinates' => $groupsOnAWorksheet,
                'count' => count($groupsOnAWorksheet),
            ]);
        } else {
            Log::warning('Can\'t process the group names');
        }


        return 'something';
    }
}
