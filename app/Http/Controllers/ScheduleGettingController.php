<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Entities\Group\Group;
use App\Http\Formatter\GettingScheduleResponseFormatter;
use App\Http\Request\GroupGettingRequest;
use App\Http\Request\ScheduleGettingRequest;
use App\Service\GettingSchedule\GettingGroup\GroupGettingService;
use App\Service\GettingSchedule\GettingProcessedScheduleRequest;
use App\Service\GettingSchedule\GettingProcessedScheduleService;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleGettingController
{
    /**
     * @param ScheduleGettingRequest $request
     * @param GettingProcessedScheduleService $gettingProcessedScheduleService
     * @param EntityManagerInterface $entityManager
     * @return array
     * @throws \App\Domain\Exception\EntityWasNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSchedule(
        ScheduleGettingRequest $request,
        GettingProcessedScheduleService $gettingProcessedScheduleService,
        EntityManagerInterface $entityManager,
        GettingScheduleResponseFormatter $formatter,
    ): array {
        $groupId = $request->input('group_id');
        $date = $request->input('date');
        $scheduleDate = new \DateTime($date);
        $groupRepository = $entityManager->getRepository(Group::class);
        $group = $groupRepository->getOneBy(['id' => $groupId]);

        $request = new GettingProcessedScheduleRequest($group, $scheduleDate);

        $schedule = $gettingProcessedScheduleService->getSchedule($request);
        return $formatter->format($schedule);
    }

    public function getGroups(
        GroupGettingRequest $request,
        GroupGettingService $groupGettingService
    ): array {
        $groupPart = $request->input('group_name');
        $groups = $groupGettingService->getByPart($groupPart);
        $groupData = [];
        foreach ($groups as $group) {
            $groupData[] = [
                'group_id' => $group['id'],
                'group_name' => $group['name'],
            ];
        }
        return $groupData;
    }
}
