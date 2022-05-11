<?php

declare(strict_types=1);

namespace App\Service\Schedule\Assembler;

use App\Domain\Entities\Group;
use App\Domain\Entities\Schedule;
use App\Service\Schedule\Processing\Dto\CreatingDto\LessonCreateDto;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Log;

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

            $group = new Group($groupName);
            $schedule = new Schedule($scheduleDateStart, $scheduleDateEnd);
            $schedule->setGroup($group);
            $group->setSchedule($schedule);
            $this->entityManager->persist($schedule);

            foreach ($lessonsDtoForGroup as $groupId => $lesson) {
                $course = $lesson->getCoursesDto()[0];
                $lessons[] = $this->lessonAssembler->create($lesson, $course, $schedule);
                // todo: group create
            }

//            $this->entityManager->persist($schedule);
        } else {
            $subgroup = [];
            foreach ($lessonsDtoForGroup as $groupId => $lesson) {
                foreach ($lesson->getCoursesDto() as $index => $course) {
                    if ($course->getRawCourse() !== '') {
                        $subgroup[$index][] = [
                            'lesson' => $lesson,
                            'course' => $course,
                        ];
                    }
                }
            }

            foreach ($subgroup as $index => $subgroupLessonData) {
                $lessonsForSubGroup = [];

                $subgroupName = $this->getSubgroupName($groupName, $index);

                // todo: get group from db
                $group = new Group($subgroupName);
                $schedule = new Schedule($scheduleDateStart, $scheduleDateEnd);
                $schedule->setGroup($group);
                $group->setSchedule($schedule);
                $this->entityManager->persist($schedule);
                foreach ($subgroupLessonData as $subgroupData) {
                    $subgroupCourse = $subgroupData['course'];
                    $subgroupLesson = $subgroupData['lesson'];
                    $lesson = $this->lessonAssembler->create($subgroupLesson, $subgroupCourse, $schedule);
                    $lessonsForSubGroup[] = $lesson;
                    $this->entityManager->persist($lesson);
                }

                $this->entityManager->persist($schedule);
//                    создать группу
            }
        }

        $this->entityManager->flush();
        return new Group($groupName);
    }

    /**
     * @param string $groupName
     * @param int $index
     * @return string
     */
    private function getSubgroupName(string $groupName, int $index): string
    {
        $subgroupName = '';
        if ($index === 0) {
            $subgroupName = self::FIRST_SUBGROUP;
        } else if ($index === 1) {
            $subgroupName = self::SECOND_SUBGROUP;
        } else {
            Log::warning('Subgroup index is too big!', [
                'subgroupIndex' => $index,
            ]);
        }
        return $groupName . ' ' . $subgroupName;
    }
}
