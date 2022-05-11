<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lesson")
 */
class Lesson
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity="Course", mappedBy="lesson")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private Course $course;

    /**
     * @ORM\ManyToOne(targetEntity="Schedule", inversedBy="lessons")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id")
     */
    private Schedule $schedule;

    /**
     * @ORM\Column(name="sequence_number", type="text", nullable=true)
     */
    private ?string $sequenceNumber;

    /**
     * @ORM\Column(name="start_time", type="text", nullable=true)
     */
    private ?string $startTime;

    /**
     * @ORM\Column(name="type_of_lesson", type="text", nullable=true)
     */
    private ?string $typeOfLesson;

    /**
     * @ORM\Column(name="classroom", type="text", nullable=true)
     */
    private ?string $classroom;

    /**
     * @ORM\Column(name="day_number", type="integer", nullable=true)
     */
    private ?int $dayNumber;

    /**
     * @param Schedule $schedule
     * @param Course $course
     * @param string|null $sequenceNumber
     * @param string|null $startTime
     * @param string|null $typeOfLesson
     * @param string|null $classroom
     */
    public function __construct(
        Schedule $schedule,
        Course  $course,
        ?string $sequenceNumber = null,
        ?string $startTime = null,
        ?string $typeOfLesson = null,
        ?string $classroom = null
    ) {
        $this->schedule = $schedule;
        $this->sequenceNumber = $sequenceNumber;
        $this->startTime = $startTime;
        $this->typeOfLesson = $typeOfLesson;
        $this->classroom = $classroom;
        $this->course = $course;
    }

    /**
     * @return Schedule
     */
    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    /**
     * @param Schedule $schedule
     */
    public function setSchedule(Schedule $schedule): void
    {
        $this->schedule = $schedule;
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
