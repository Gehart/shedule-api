<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Entities\Group\Group;
use App\Http\Request\StandardRequest;
use App\Service\GettingSchedule\GettingProcessedScheduleRequest;
use App\Service\GettingSchedule\GettingProcessedScheduleService;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;

class ScheduleGettingController
{
    /**
     * @param StandardRequest $request
     * @param GettingProcessedScheduleService $gettingProcessedScheduleService
     * @param EntityManagerInterface $entityManager
     * @return array
     * @throws \App\Domain\Exception\EntityWasNotFoundException
     */
    public function getSchedule(
        StandardRequest $request,
        GettingProcessedScheduleService $gettingProcessedScheduleService,
        EntityManagerInterface $entityManager,
    ): array {
        $groupId = $request->input('groupId', 2);
        $date = $request->input('date', '2022-05-26');
        $scheduleDate = new \DateTime($date);
        $groupRepository = $entityManager->getRepository(Group::class);
        $group = $groupRepository->getOneBy(['id' => $groupId]);

        $request = new GettingProcessedScheduleRequest($group, $scheduleDate);

        $schedule = $gettingProcessedScheduleService->getSchedule($request);
        return [
            'id' => $schedule->getId(),
        ];
    }

    public function getGroup(StandardRequest $request)
    {
        $groupPart = $request->input('groupName');
    }
}
