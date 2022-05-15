<?php

declare(strict_types=1);

namespace App\Http\Assembler;

use App\Domain\Entities\Group\Group;
use App\Domain\Entities\Group\GroupRepository;
use App\Http\Request\IcalFileGettingRequest;
use App\Service\ConvertSchedule\Dto\IcalFileGeneratingRequest;
use Doctrine\ORM\EntityManagerInterface;

class IcalFileGenerationRequestAssembler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(IcalFileGettingRequest $request): IcalFileGeneratingRequest
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository(Group::class);
        $group = $groupRepository->getOneBy(['id' => $request->input('group_id')]);
        $date = new \DateTime($request->input('date'));
        return new IcalFileGeneratingRequest($group, $date);
    }
}
