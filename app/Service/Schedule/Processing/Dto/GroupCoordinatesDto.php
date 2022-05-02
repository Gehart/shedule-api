<?php

namespace App\Service\Schedule\Processing\Dto;

class GroupCoordinatesDto
{
    public function __construct(
        readonly private string $groupName,
        readonly private string $coordinate,
        readonly private string $groupRange,
    ) {
    }

    /**
     * @return string
     */
    public function getGroupRange(): string
    {
        return $this->groupRange;
    }

    /**
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->groupName;
    }

    /**
     * @return string
     */
    public function getCoordinate(): string
    {
        return $this->coordinate;
    }
}
