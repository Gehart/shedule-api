<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Entities\Group\Group;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class LessonRepository extends EntityRepository
{
/*
select course.teacher, *
from lesson
join schedule s1
on lesson.schedule_id = s1.id
join "groups"
on s1.group_id = "groups".id
join course
on course.lesson_id = lesson.id
left outer join schedule s2
on (s1.group_id  = s2.group_id  and s1.created < s2.created)
where '2022-05-16' between s1.day_start and s1.day_end
and s2.group_id is null
and course.teacher ~* 'дрозин'
*/

    /**
     * @param string $teacherName
     * @param \DateTimeInterface $date
     * @return array<Lesson>
     */
    public function getLessonsForTeacher(string $teacherName, \DateTimeInterface $date): array
    {
        $queryBuilder = $this->createQueryBuilder('lesson');
        $expr = $queryBuilder->expr();
        $queryBuilder->innerJoin(Schedule::class, 's1', Join::WITH, 'lesson.schedule = s1.id')
            ->innerJoin(Group::class, 'groups', Join::WITH, 's1.group = groups.id')
            ->innerJoin(Course::class, 'course', Join::WITH, 'course.lesson = lesson.id')
            ->leftJoin(Schedule::class, 's2', Join::WITH, 's1.group = s2.group and s1.created < s2.created')
            ->where($expr->between(':date', 's1.dayStart', 's1.dayEnd'))
            ->andWhere($expr->isNull('s2.group'))
            ->andWhere('LOWER(course.teacher) LIKE :teacherName')
            ->setParameter(':teacherName', mb_strtolower("%{$teacherName}%"))
            ->setParameter(':date', $date->format(DATE_ATOM));


        return $queryBuilder->getQuery()->getResult();
    }
}
