<?php

declare(strict_types=1);

namespace App\Domain\Entities\Group;

use App\Domain\Entities\Schedule;
use App\Domain\Exception\EntityWasNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method null|Group findOneBy(array $criteria, array $orderBy = null)
 */
class GroupRepository extends EntityRepository
{
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Group
     * @throws EntityWasNotFoundException
     */
    public function getOneBy(array $criteria, array $orderBy = null): Group
    {
        $group = $this->findOneBy($criteria, $orderBy);
        if (!$group instanceof Group) {
            throw new EntityWasNotFoundException();
        }
        return $group;
    }

    public function getByGroupNamePart($groupName)
    {
        return $this->createQueryBuilder('groups')
            ->select(['groups.id', 'groups.name'])
            ->innerJoin(Schedule::class, 'schedule', Join::WITH, 'groups.id = schedule.group')
            ->orWhere('LOWER(groups.name) LIKE :groupName')
            ->setParameter(':groupName', mb_strtolower("{$groupName}%"))
            ->groupBy('groups.id')
            ->getQuery()
            ->getResult();
    }
}
