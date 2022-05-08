<?php

namespace App\Service\Schedule;

use App\Service\Schedule\Dictionary\ScheduleDictionary;
use App\Service\Schedule\Processing\DayName\Dto\DayCellDto;
use App\Service\Schedule\Processing\Dto\CreatingDto\CourseCreateDto;
use App\Service\Schedule\Processing\Dto\CreatingDto\LessonCreateDto;
use App\Service\Schedule\Processing\Dto\GroupCoordinatesDto;
use App\Service\Schedule\Processing\Utils\ProcessingUtils;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LessonGettingService
{
    public const SEQUENCE_NUMBER_REGEX = '/\d.?пара/',
        START_TIME_REGEX = '/\d{1,2}:\d{1,2}/',
        TYPE_OF_LESSON_REGEX = '/\w{1,3}/u',
        MAX_CLASSROOM_STRING_LENGTH = 15,
        FIO_REGEX = '/\w{3,}\s\w\.\s?\w\./u';


    public function __construct(
        private ProcessingUtils $utils,
        private ScheduleDictionary $dictionary,
    ) {
    }

    /**
     * @param Worksheet $worksheet
     * @param string $columnOfGroup
     * @param int $rowIndex
     * @param GroupCoordinatesDto $group
     * @param DayCellDto $currentDay
     * @return LessonCreateDto|null
     * @throws Exception
     */
    public function getLessonDto(
        Worksheet $worksheet,
        string $columnOfGroup,
        int $rowIndex,
        GroupCoordinatesDto $group,
        DayCellDto $currentDay
    ): ?LessonCreateDto
    {
        $courses = $this->getCourses($worksheet, $columnOfGroup, $rowIndex, $group);
        if (count($courses) === 0) {
            return null;
        }

        $isMilitaryFacultyLesson = $courses[0]->isMilitaryFaculty();
        if ($isMilitaryFacultyLesson) {
            return new LessonCreateDto($courses, $currentDay->getDay(), $isMilitaryFacultyLesson);
        }

        [$lessonSequenceNumber, $lessonColumn] = $this->getLessonNumber($worksheet, $columnOfGroup, $rowIndex);

        $startTimeColumn = $this->utils->getIncreasedColumnAddress($lessonColumn, 1);
        $startTime = $this->utils->getCellValueByColumnAndRow($worksheet, $startTimeColumn, $rowIndex);
        if (!preg_match(self::START_TIME_REGEX, $startTime)) {
            Log::warning('Start time does not match the pattern!', [
                'startTime' => $startTime,
                'cell' => $columnOfGroup . $rowIndex,
            ]);
        }

        $courseEndColumn = $this->getCourseEndColumn($group, $worksheet, $columnOfGroup, $rowIndex);

        $typeOfLessonColumn = $this->utils->getIncreasedColumnAddress($courseEndColumn, 1);
        $typeOfLesson = $this->utils->getCellValueByColumnAndRow($worksheet, $typeOfLessonColumn, $rowIndex);
        if (!preg_match(self::TYPE_OF_LESSON_REGEX, $typeOfLesson)) {
            Log::warning('Start time does not match the pattern!', [
                'typeOfLesson' => $typeOfLesson,
                'cell' => $columnOfGroup . $rowIndex,
            ]);
        }

        $classroomColumn = $this->utils->getIncreasedColumnAddress($courseEndColumn, 2);
        $classroom = $this->utils->getCellValueByColumnAndRow($worksheet, $classroomColumn, $rowIndex);
        if (strlen($classroom) > self::MAX_CLASSROOM_STRING_LENGTH) {
            Log::warning('Classroom has quite long string', [
                'classroom' => $classroom,
                'cell' => $columnOfGroup . $rowIndex,
            ]);
        }

//        $dayNumber =  $currentDay->getDay()->getNumber();

        return new LessonCreateDto(
            $courses,
            $currentDay->getDay(),
            $isMilitaryFacultyLesson,
            $lessonSequenceNumber,
            $startTime,
            $typeOfLesson,
            $classroom,
        );
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
     * @return array<CourseCreateDto>
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

        $coursesDto = [];
        foreach ($cellValues as $rawCourseValue) {
            $isMilitaryFacultyCourse = $this->isMilitaryFacultyCourse($rawCourseValue);
            $splitValue = $this->splitRawCourseValue($rawCourseValue);
            $courseName = $courseTeacher = null;
            if ($splitValue) {
                [$courseName, $courseTeacher] =  $splitValue;
                if (mb_strlen($courseName) < 4 || mb_strlen($courseName) < 4) {
                    Log::notice('Strange course name and teacher values', [
                        'courseName' => $courseName,
                        'teacher' => $courseTeacher,
                        'rawValue' => $rawCourseValue,
                    ]);
                }

            }

            $coursesDto[] = new CourseCreateDto($rawCourseValue, $isMilitaryFacultyCourse, $courseTeacher, $courseName);
        }

        return $coursesDto;
    }

    /**
     * @param string $courseRawName
     * @return bool
     */
    private function isMilitaryFacultyCourse(string $courseRawName): bool
    {
        $militaryFacultyAliases = $this->dictionary->getMilitaryFacultyData()['aliases'];
        foreach ($militaryFacultyAliases as $alias) {
            if (mb_strtolower($alias) === mb_strtolower($courseRawName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $courseRawValue
     * @return array<string>|null
     */
    private function splitRawCourseValue(string $courseRawValue): ?array
    {
        $matches = [];
        if (preg_match(self::FIO_REGEX, $courseRawValue, $matches)) {
            $firstMatchString = $matches[0];
            $fioOffset = mb_strpos($courseRawValue, $firstMatchString);
            $courseName = trim(mb_substr($courseRawValue, 0, $fioOffset));
            $courseTeacher = trim(mb_substr($courseRawValue, $fioOffset));
            return [$courseName, $courseTeacher];
        }
        return null;
    }
}
