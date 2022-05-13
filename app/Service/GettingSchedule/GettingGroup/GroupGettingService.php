<?php

declare(strict_types=1);

namespace App\Service\GettingSchedule\GettingGroup;

use App\Domain\Entities\Group\Group;
use App\Domain\Entities\Group\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupGettingService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param $groupName
     * @return array<Group>
     */
    public function getByPart($groupName): array
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository(Group::class);
        return $groupRepository->getByGroupNamePart($groupName);
    }
}
