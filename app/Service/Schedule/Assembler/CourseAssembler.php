<?php

namespace App\Service\Schedule\Assembler;

use App\Domain\Entities\Course;
use App\Service\Schedule\Processing\Dto\CreatingDto\CourseCreateDto;

class CourseAssembler
{
    /**
     * @param CourseCreateDto $courseCreateDto
     * @return Course
     */
    public function create(CourseCreateDto $courseCreateDto): Course
    {
        return new Course(
            $courseCreateDto->getRawCourse(),
            $courseCreateDto->getTeacher(),
            $courseCreateDto->getCourseName()
        );
    }
}
