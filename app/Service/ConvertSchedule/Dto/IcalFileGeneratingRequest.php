<?php

declare(strict_types=1);

namespace App\Service\ConvertSchedule\Dto;

use App\Domain\Entities\Group\Group;
use App\Domain\Entities\Schedule;

class IcalFileGeneratingRequest
{
    public function __construct(
        private Group $group,
        private \DateTimeInterface $date,
    ) {
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     */
    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }
}
