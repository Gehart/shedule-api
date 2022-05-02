<?php

namespace App\Entities;

class Course
{
    private string $rawCourse;
    private ?string $teacher;
    private ?string $courseName;

    /**
     * @param string $rawCourse
     * @param string|null $teacher
     * @param string|null $courseName
     */
    public function __construct(string $rawCourse, ?string $teacher = null, ?string $courseName = null)
    {
        $this->rawCourse = $rawCourse;
        $this->teacher = $teacher;
        $this->courseName = $courseName;
    }

    /**
     * @return string
     */
    public function getRawCourse(): string
    {
        return $this->rawCourse;
    }

    /**
     * @param string $rawCourse
     */
    public function setRawCourse(string $rawCourse): void
    {
        $this->rawCourse = $rawCourse;
    }

    /**
     * @return string|null
     */
    public function getTeacher(): ?string
    {
        return $this->teacher;
    }

    /**
     * @param string|null $teacher
     */
    public function setTeacher(?string $teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return string|null
     */
    public function getCourseName(): ?string
    {
        return $this->courseName;
    }

    /**
     * @param string|null $courseName
     */
    public function setCourseName(?string $courseName): void
    {
        $this->courseName = $courseName;
    }
}
