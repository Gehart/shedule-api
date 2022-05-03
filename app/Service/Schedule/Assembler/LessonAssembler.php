<?php

namespace App\Service\Schedule\Assembler;

use App\Entities\Lesson;
use App\Service\Schedule\Processing\Dto\CreatingDto\LessonCreateDto;

class LessonAssembler
{

    public function __construct(
        private CourseAssembler $courseAssembler,
    ) {
    }

    public function create(LessonCreateDto $lessonCreateDto): Lesson
    {
        $courses = [];
        foreach ($lessonCreateDto->getCoursesDto() as $courseDto) {
            $courses[] = $this->courseAssembler->create($courseDto);
        }

        return new Lesson(
            $courses,
            $lessonCreateDto->getSequenceNumber(),
            $lessonCreateDto->getStartTime(),
            $lessonCreateDto->getTypeOfLesson(),
            $lessonCreateDto->getClassroom(),
        );
    }
}
