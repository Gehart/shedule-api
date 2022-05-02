<?php

namespace App\Service\Schedule;

use App\Entities\Course;
use App\Entities\Lesson;
use App\Service\Schedule\Processing\Dto\GroupCoordinatesDto;
use App\Service\Schedule\Processing\Utils\ProcessingUtils;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LessonGettingService
{
    public const SEQUENCE_NUMBER_REGEX = '/\d.?пара/';

    public function __construct(
        private ProcessingUtils $utils,
    ) {
    }

    /**
     * @param Worksheet $worksheet
     * @param string $columnOfGroup
     * @param int $rowIndex
     * @param GroupCoordinatesDto $group
     * @return Lesson|null
     * @throws Exception
     */
    public function getLesson(Worksheet $worksheet, string $columnOfGroup, int $rowIndex, GroupCoordinatesDto $group): ?Lesson
    {
        // shift until hit the group pair and time
        // get type and classroom on the left
        $courses = $this->getCourses($worksheet, $columnOfGroup, $rowIndex, $group);
        if (count($courses) === 0) {
            return null;
        }

        [$lessonSequenceNumber, $lessonColumn] = $this->getLessonNumber($worksheet, $columnOfGroup, $rowIndex);

        $startTimeColumn = $this->utils->getIncreasedColumnAddress($lessonColumn, 1);
        $startTime = $this->utils->getCellValueByColumnAndRow($worksheet, $startTimeColumn, $rowIndex);

        $courseEndColumn = $this->getCourseEndColumn($group, $worksheet, $columnOfGroup, $rowIndex);

        $typeOfLessonColumn = $this->utils->getIncreasedColumnAddress($courseEndColumn, 1);
        $typeOfLesson = $this->utils->getCellValueByColumnAndRow($worksheet, $typeOfLessonColumn, $rowIndex);

        $classroomColumn = $this->utils->getIncreasedColumnAddress($courseEndColumn, 2);
        $classroom = $this->utils->getCellValueByColumnAndRow($worksheet, $classroomColumn, $rowIndex);
        return new Lesson($courses, $lessonSequenceNumber, $startTime, $typeOfLesson, $classroom);
}

/**
     * @param Worksheet $worksheet
     * @param string $columnOfGroup
     * @param int $rowIndex
     * @return array
     * @throws Exception
     */
    public function getLessonNumber(Worksheet $worksheet, string $columnOfGroup, int $rowIndex): array
    {
        $columnIterator = $worksheet->getColumnIterator(ScheduleByWeekStrategyService::FIRST_COLUMN_LETTER, $columnOfGroup);
        $columnIterator->seek($columnOfGroup);
        $lessonSequenceNumber = null;
        $columnIndex = null;

        while ($columnIterator->valid()) {
            $columnIndex = $columnIterator->current()->getColumnIndex();

            $cellValue = $this->utils->getCellValueByColumnAndRow($worksheet, $columnIndex, $rowIndex);

            if (preg_match(self::SEQUENCE_NUMBER_REGEX, $cellValue)) {
                $lessonSequenceNumber = $cellValue;
                break;
            }

            $columnIterator->prev();
        }

        return [$lessonSequenceNumber, $columnIndex];
    }

    /**
     * @param GroupCoordinatesDto $group
     * @param string $columnOfGroup
     * @param int $rowIndex
     * @return string
     * @throws Exception
     */
    private function getCourseEndColumn(GroupCoordinatesDto $group, Worksheet $worksheet, string $columnOfGroup, int $rowIndex): mixed
    {
        $courseSplitRange = Coordinate::splitRange($group->getGroupRange());
        $courseEndRange = array_slice($courseSplitRange, -1)[0];
        $courseEndCoordinate = $courseEndRange[1];

        [$courseEndColumnAddress] = Coordinate::coordinateFromString($courseEndCoordinate);
        $courseEndColumnIndex = Coordinate::columnIndexFromString($courseEndColumnAddress);



        // Обработка лекции и общепотоковых пар
        $currentCellRange = $worksheet->getCell($columnOfGroup . $rowIndex)->getMergeRange();
        if ($currentCellRange) {
            [$currentCellStart, $currentCellEnd] = Coordinate::rangeBoundaries($currentCellRange);
            $currentCellEndCoordinate = $currentCellEnd[0];

            if ($currentCellEndCoordinate > $courseEndColumnIndex) {
                $courseEndColumnIndex = $currentCellEndCoordinate;
            }
        }


//        [$courseEndColumn] = Coordinate::coordinateFromString($courseEndCoordinate);
        return Coordinate::stringFromColumnIndex($courseEndColumnIndex);
    }

    /**
     * @param Worksheet $worksheet
     * @param string $columnOfGroup
     * @param int $rowIndex
     * @param GroupCoordinatesDto $group
     * @return array<Course>
     */
    private function getCourses(Worksheet $worksheet, string $columnOfGroup, int $rowIndex, GroupCoordinatesDto $group): array
    {
        [$startCellCoordinates, $endCellCoordinates] = Coordinate::getRangeBoundaries($group->getGroupRange());
        $startCellCoordinates[1] = $rowIndex;
        $endCellCoordinates[1] = $rowIndex;
        $lessonRange = $startCellCoordinates[0] . $startCellCoordinates[1] . ':' . $endCellCoordinates[0] . $endCellCoordinates[1];
        $cellCoordinates = Coordinate::extractAllCellReferencesInRange($lessonRange);

        $cells = [];
        foreach ($cellCoordinates as $cellCoordinate) {
            $cells[] = $worksheet->getCell($cellCoordinate);
        }

        $cellValues = [];
        /** @var Cell $cell */
        foreach ($cells as $cell) {
            $cellValues[] = $this->utils->getCellValueWithinRange($worksheet, $cell);
        }

        $allCellValueIsEmpty = true;
        foreach ($cellValues as $cellValue) {
            if (!empty($cellValue)) {
                $allCellValueIsEmpty = false;
            }
        }
        if ($allCellValueIsEmpty) {
            return [];
        }

        $courses = [];
        foreach ($cellValues as $cellValue) {
            $courses[] = new Course($cellValue);
        }

        return $courses;
    }
}
