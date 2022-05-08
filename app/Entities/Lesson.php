<?php

namespace App\Entities;

class Lesson
{
    private ?string $sequenceNumber;
    private ?string $startTime;
    private ?string $typeOfLesson;
    private ?string $classroom;
    private Course $course;
    private ?int $dayNumber;

    /**
     * @param string|null $sequenceNumber
     * @param string|null $startTime
     * @param string|null $typeOfLesson
     * @param string|null $classroom
     * @param Course $courses
     */
    public function __construct(
        Course $courses,
        ?string $sequenceNumber = null,
        ?string $startTime = null,
        ?string $typeOfLesson = null,
        ?string $classroom = null
    ) {
        $this->sequenceNumber = $sequenceNumber;
        $this->startTime = $startTime;
        $this->typeOfLesson = $typeOfLesson;
        $this->classroom = $classroom;
        $this->course = $courses;
    }

    /**
     * @return string|null
     */
    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }

    /**
     * @param string|null $sequenceNumber
     */
    public function setSequenceNumber(?string $sequenceNumber): void
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    /**
     * @return string|null
     */
    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    /**
     * @param string|null $startTime
     */
    public function setStartTime(?string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return string|null
     */
    public function getTypeOfLesson(): ?string
    {
        return $this->typeOfLesson;
    }

    /**
     * @param string|null $typeOfLesson
     */
    public function setTypeOfLesson(?string $typeOfLesson): void
    {
        $this->typeOfLesson = $typeOfLesson;
    }

    /**
     * @return string|null
     */
    public function getClassroom(): ?string
    {
        return $this->classroom;
    }

    /**
     * @param string|null $classroom
     */
    public function setClassroom(?string $classroom): void
    {
        $this->classroom = $classroom;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @param array<Course> $courses
     */
    public function setCourses(array $courses): void
    {
        $this->course = $courses;
    }

    /**
     * @return int|null
     */
    public function getDayNumber(): ?int
    {
        return $this->dayNumber;
    }

    /**
     * @param int $dayNumber
     */
    public function setDayNumber(int $dayNumber): void
    {
        $this->dayNumber = $dayNumber;
    }
}
