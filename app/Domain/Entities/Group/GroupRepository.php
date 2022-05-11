<?php

declare(strict_types=1);

namespace App\Domain\Entities\Group;

use Doctrine\ORM\EntityRepository;

/**
 * @method null|Group findOneBy(array $criteria, array $orderBy = null)
 */
class GroupRepository extends EntityRepository
{
}
