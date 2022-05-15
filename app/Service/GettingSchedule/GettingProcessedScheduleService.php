<?php

declare(strict_types=1);

namespace App\Service\GettingSchedule;

use App\Domain\Entities\Group\Group;
use App\Domain\Entities\Schedule;
use App\Domain\Entities\ScheduleRepository;
use App\Domain\Exception\EntityWasNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class GettingProcessedScheduleService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param GettingProcessedScheduleRequest $request
     * @return Schedule|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws EntityWasNotFoundException
     */
    public function getSchedule(GettingProcessedScheduleRequest $request): ?Schedule
    {
        /** @var ScheduleRepository $scheduleRepository */
        $scheduleRepository = $this->entityManager->getRepository(Schedule::class);

        $group = $request->getGroup();

        $schedule = $scheduleRepository->getScheduleForGroup($group, $request->getScheduleDate());
        if (!$schedule instanceof Schedule) {
            throw new EntityWasNotFoundException();
        }
        return $schedule;
    }
}
