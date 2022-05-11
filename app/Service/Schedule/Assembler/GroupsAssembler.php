<?php

declare(strict_types=1);

namespace App\Service\Schedule\Assembler;

use App\Domain\Entities\Group\Group;
use App\Domain\Entities\Group\GroupRepository;
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
     * @return array<Group>
     */
    public function create(string $groupName, array $lessonsDtoForGroup, \DateTime $scheduleDateStart, \DateTime $scheduleDateEnd): array
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
        if ($isNeedOnlyOneGroup) {

            $group = $this->getGroup($groupName);
            $schedule = new Schedule($scheduleDateStart, $scheduleDateEnd);
            $schedule->setGroup($group);
            $group->addSchedule($schedule);
            $this->entityManager->persist($schedule);

            foreach ($lessonsDtoForGroup as $groupId => $lesson) {
                $course = $lesson->getCoursesDto()[0];
            }
            $groups[] = $group;
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
                $subgroupName = $this->getSubgroupName($groupName, $index);

                $group = $this->getGroup($subgroupName);
                $schedule = new Schedule($scheduleDateStart, $scheduleDateEnd);
                $schedule->setGroup($group);
                $group->addSchedule($schedule);
                $this->entityManager->persist($schedule);
                foreach ($subgroupLessonData as $subgroupData) {
                    $subgroupCourse = $subgroupData['course'];
                    $subgroupLesson = $subgroupData['lesson'];
                    $lesson = $this->lessonAssembler->create($subgroupLesson, $subgroupCourse, $schedule);
                    $this->entityManager->persist($lesson);
                }

                $this->entityManager->persist($schedule);
                $groups[] = $group;
                break;
            }
        }

        $this->entityManager->flush();
        return $groups;
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

    /**
     * @param string $groupName
     * @return Group
     */
    private function getGroup(string $groupName): Group
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository(Group::class);
        $group = $groupRepository->findOneBy(['name' => $groupName]);

        if (!$group instanceof Group) {
            $group = new Group($groupName);
        }

        return $group;
    }
}
