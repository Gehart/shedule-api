<?php

namespace App\Service\Schedule\Processing\Dto\CreatingDto;

class CourseCreateDto
{
    /**
     * @param bool $isMilitaryFaculty
     * @param string $rawCourse
     * @param string|null $teacher
     * @param string|null $courseName
     */
    public function __construct(
        private string $rawCourse,
        private bool $isMilitaryFaculty = false,
        private ?string $teacher = null,
        private ?string $courseName = null,
    ) {
    }

    /**
     * @return string
     */
    public function getRawCourse(): string
    {
        return $this->rawCourse;
    }

    /**
     * @return string|null
     */
    public function getTeacher(): ?string
    {
        return $this->teacher;
    }

    /**
     * @return string|null
     */
    public function getCourseName(): ?string
    {
        return $this->courseName;
    }

    /**
     * @return bool
     */
    public function isMilitaryFaculty(): bool
    {
        return $this->isMilitaryFaculty;
    }
}
