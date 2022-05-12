<?php

declare(strict_types=1);

namespace App\Service\GettingSchedule;

use App\Domain\Entities\Group\Group;

class GettingProcessedScheduleRequest
{
    /**
     * @param Group $groupId
     * @param \DateTimeInterface $scheduleDate
     */
    public function __construct(
        private Group $groupId,
        private \DateTimeInterface $scheduleDate,
    ) {
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->groupId;
    }

    /**
     * @param Group $groupId
     */
    public function setGroup(Group $groupId): void
    {
        $this->groupId = $groupId;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getScheduleDate(): \DateTimeInterface
    {
        return $this->scheduleDate;
    }

    /**
     * @param \DateTimeInterface $scheduleDate
     */
    public function setScheduleDate(\DateTimeInterface $scheduleDate): void
    {
        $this->scheduleDate = $scheduleDate;
    }
}
