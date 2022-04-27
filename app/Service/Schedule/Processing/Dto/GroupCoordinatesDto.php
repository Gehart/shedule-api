<?php

namespace App\Service\Schedule\Processing\Dto;

class GroupCoordinatesDto
{
    public function __construct(
        readonly private string $groupName,
        readonly private string $coordinate,
    ) {
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
