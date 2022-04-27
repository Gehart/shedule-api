<?php

namespace tests\Service\Schedule\Processing;

use App\Service\Schedule\Exception\CannotFindFirstGroupNameException;
use App\Service\Schedule\Processing\GroupCoordinatesProcessingService;
use PhpOffice\PhpSpreadsheet\Exception;
use tests\Service\Schedule\TestCaseWithSpreadsheet;

class GroupCoordinatesProcessingServiceTest extends TestCaseWithSpreadsheet
{
    public const COUNT_OF_GROUP_NAMES = 8;

    private GroupCoordinatesProcessingService $groupCoordinatesProcessingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->groupCoordinatesProcessingService = app()->make(GroupCoordinatesProcessingService::class);
    }

    /**
     * @throws Exception
     * @throws CannotFindFirstGroupNameException
     */
    public function testFindAGroupsCoordinate()
    {
        $worksheet = $this->getFirstWorksheet();
        $groupCoordinates = $this->groupCoordinatesProcessingService->findAGroupsCoordinate($worksheet);

        self::assertCount(self::COUNT_OF_GROUP_NAMES, $groupCoordinates);
        self::assertTrue((bool) preg_match(GroupCoordinatesProcessingService::GROUP_NAME_REGEX, $groupCoordinates[0]->getGroupName()));
    }
}
