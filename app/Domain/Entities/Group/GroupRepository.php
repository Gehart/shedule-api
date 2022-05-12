<?php

declare(strict_types=1);

namespace App\Domain\Entities\Group;

use App\Domain\Exception\EntityWasNotFoundException;
use Doctrine\ORM\EntityRepository;

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
}
