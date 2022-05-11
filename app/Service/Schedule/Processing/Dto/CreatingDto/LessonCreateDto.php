<?php

namespace App\Service\Schedule\Processing\Dto\CreatingDto;

use App\Domain\Entities\Day;

class LessonCreateDto
{
    private bool $isMilitaryFacultyLesson;
    private ?string $sequenceNumber;
    private ?string $startTime;
    private ?string $typeOfLesson;
    private ?string $classroom;
    private Day $day;

    /**
     * @var array<CourseCreateDto>
     */
    private array $coursesDto;

    /**
     * @param array $coursesDto
     * @param Day $day
     * @param bool $isMilitaryFacultyLesson
     * @param string|null $sequenceNumber
     * @param string|null $startTime
     * @param string|null $typeOfLesson
     * @param string|null $classroom
     */
    public function __construct(
        array   $coursesDto,
        Day $day,
        bool    $isMilitaryFacultyLesson = false,
        ?string $sequenceNumber = null,
        ?string $startTime = null,
        ?string $typeOfLesson = null,
        ?string $classroom = null,
    ) {
        $this->coursesDto = $coursesDto;
        $this->day = $day;
        $this->isMilitaryFacultyLesson = $isMilitaryFacultyLesson;
        $this->sequenceNumber = $sequenceNumber;
        $this->startTime = $startTime;
        $this->typeOfLesson = $typeOfLesson;
        $this->classroom = $classroom;
    }

    /**
     * @return bool
     */
    public function isMilitaryFacultyLesson(): bool
    {
        return $this->isMilitaryFacultyLesson;
    }

    /**
     * @return string|null
     */
    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }

    /**
     * @return string|null
     */
    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    /**
     * @return string|null
     */
    public function getTypeOfLesson(): ?string
    {
        return $this->typeOfLesson;
    }

    /**
     * @return string|null
     */
    public function getClassroom(): ?string
    {
        return $this->classroom;
    }

    /**
     * @return CourseCreateDto[]
     */
    public function getCoursesDto(): array
    {
        return $this->coursesDto;
    }

    /**
     * @return Day
     */
    public function getDay(): Day
    {
        return $this->day;
    }
}
