<?php

declare(strict_types=1);

namespace App\Service\Schedule\Assembler;

use App\Entities\Group;
use App\Entities\Schedule;
use App\Service\Schedule\Processing\Dto\CreatingDto\CourseCreateDto;
use App\Service\Schedule\Processing\Dto\CreatingDto\LessonCreateDto;
use Doctrine\ORM\EntityManagerInterface;

class GroupsAssembler
{
    public const
        FIRST_SUBGROUP = '1 подгруппа',
        SECOND_SUBGROUP = '2 подгруппа';

    public function __construct(
        private LessonAssembler $lessonAssembler,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param string $groupName
     * @param array<string, LessonCreateDto> $lessonsDtoForGroup
     * @param \DateTime $scheduleDateStart
     * @param \DateTime $scheduleDateEnd
     * @return Group
     */
    public function create(string $groupName, array $lessonsDtoForGroup, \DateTime $scheduleDateStart, \DateTime $scheduleDateEnd): Group
    {
        // если они одинаковы, то создавать только одну группу, если не одинаковы - то 2 группы
        // если не одинаковы, создавать группа_название + подгруппа
        $isNeedOnlyOneGroup = true;
        foreach ($lessonsDtoForGroup as $lesson) {
            $coursesDto = $lesson->getCoursesDto();
            if (count($coursesDto) < 2) {
                continue;
            }

            if ($coursesDto[0] !== $coursesDto[1]) {
                $isNeedOnlyOneGroup = false;
                break;
            }
        }

        $groups = [];
        $lessons = [];
        if ($isNeedOnlyOneGroup) {
            foreach ($lessonsDtoForGroup as $groupName => $lesson) {
                $course = $lesson->getCoursesDto()[0];
                $lessons[] = $this->lessonAssembler->create($lesson, $course);
                // todo: group create
            }

            $schedule = new Schedule($lessons, $scheduleDateStart, $scheduleDateEnd);
//            $this->entityManager->persist($schedule);
        } else {
            $subgroup = [];
            foreach ($lessonsDtoForGroup as $groupName => $lesson) {
                foreach ($lesson->getCoursesDto() as $index => $course) {
                    if ($course->getRawCourse() !== '') {
                        $subgroup[$index][] = [
                            'lesson' => $lesson,
                            'course' => $course,
                        ];
                    }
                }
            }

            foreach ($subgroup as $subgroupLessonData) {
                $lessonsForSubGroup = [];
                foreach ($subgroupLessonData as $subgroupData) {
                    $subgroupCourse = $subgroupData['course'];
                    $subgroupLesson = $subgroupData['lesson'];
                    $lessonsForSubGroup[] = $this->lessonAssembler->create($subgroupLesson, $subgroupCourse);
                }

                $schedule = new Schedule($lessonsForSubGroup, $scheduleDateStart, $scheduleDateEnd);
//                    создать группу
            }

//            $this->entityManager->persist($schedule);
        }

        return new Group();
    }
}
