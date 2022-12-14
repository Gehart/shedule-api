<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Entities\Group\Group;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class ScheduleRepository extends EntityRepository
{
    /**
     * @param Group $group
     * @param \DateTimeInterface|null $date
     * @return Schedule|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getScheduleForGroup(Group $group, ?\DateTimeInterface $date): ?Schedule
    {
        $queryBuilder = $this->createQueryBuilder('schedule');
        $expr = $queryBuilder->expr();
        $queryBuilder->join(Group::class, 'groups', Join::WITH, $expr->eq('schedule.group', ':groupId'))
            ->setParameter(':groupId', $group->getId());

        if (!$date) {
            $queryBuilder->andWhere($expr->between(':date', 'schedule.dayStart', 'schedule.dayEnd'))
                ->setParameter(':date', $date->format(DATE_ATOM));
        }

        $queryBuilder->orderBy('schedule.created', 'DESC');
        $queryBuilder->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
