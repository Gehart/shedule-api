<?php

namespace App\Entities;

class Lesson
{
    private ?string $sequenceNumber;
    private ?string $startTime;
    private ?string $typeOfLesson;
    private ?string $classroom;

    /**
     * @var array<Course>
     */
    private array $courses;

    /**
     * @param string|null $sequenceNumber
     * @param string|null $startTime
     * @param string|null $typeOfLesson
     * @param string|null $classroom
     * @param array<Course> $courses
     */
    public function __construct(
        array $courses,
        ?string $sequenceNumber = null,
        ?string $startTime = null,
        ?string $typeOfLesson = null,
        ?string $classroom = null
    ) {
        $this->sequenceNumber = $sequenceNumber;
        $this->startTime = $startTime;
        $this->typeOfLesson = $typeOfLesson;
        $this->classroom = $classroom;
        $this->courses = $courses;
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
     * @return array<Course>
     */
    public function getCourses(): array
    {
        return $this->courses;
    }

    /**
     * @param array<Course> $courses
     */
    public function setCourses(array $courses): void
    {
        $this->courses = $courses;
    }
}
