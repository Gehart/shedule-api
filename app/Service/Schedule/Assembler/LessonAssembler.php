<?php

namespace App\Service\Schedule\Assembler;

use App\Domain\Entities\Lesson;
use App\Domain\Entities\Schedule;
use App\Service\Schedule\Processing\Dto\CreatingDto\CourseCreateDto;
use App\Service\Schedule\Processing\Dto\CreatingDto\LessonCreateDto;
use Doctrine\ORM\EntityManagerInterface;

class LessonAssembler
{

    public function __construct(
        private CourseAssembler $courseAssembler,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(LessonCreateDto $lessonCreateDto, CourseCreateDto $courseCreateDto, Schedule $schedule): Lesson
    {
        $courses = [];
        $coursesDto = $lessonCreateDto->getCoursesDto();

//        $coursesAreEqual = true;
//        $lastCourseNameValue = null;
//        foreach ($coursesDto as $courseDto) {
//            if ($lastCourseNameValue !== null && $lastCourseNameValue !== $courseDto->getRawCourse()) {
//                $coursesAreEqual = false;
//                break;
//            }
//            $lastCourseNameValue = $courseDto->getRawCourse();
//        }
//
//        if ($coursesAreEqual) {
//            $coursesDto = count($coursesDto) ? $coursesDto : [];
//        }
//

//        foreach ($coursesDto as $courseDto) {
//            $course = $this->courseAssembler->create($courseDto);
//            $this->entityManager->persist($course);
//            $courses[] = $course;
//        }
        $course = $this->courseAssembler->create($courseCreateDto);
        $this->entityManager->persist($course);
        $lesson = new Lesson(
            $schedule,
            $course,
            $lessonCreateDto->getSequenceNumber(),
            $lessonCreateDto->getStartTime(),
            $lessonCreateDto->getTypeOfLesson(),
            $lessonCreateDto->getClassroom(),
        );

        $lesson->setDayNumber($lessonCreateDto->getDay()->getNumber());
        $course->setLesson($lesson);

        return $lesson;
    }
}
