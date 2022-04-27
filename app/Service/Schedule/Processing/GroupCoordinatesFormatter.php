<?php

namespace App\Service\Schedule\Processing;

use App\Service\Schedule\Processing\Dto\GroupCoordinatesDto;

class GroupCoordinatesFormatter
{
    public function format(GroupCoordinatesDto $groupCoordinatesDto): array
    {
        return [
            'group_name' => $groupCoordinatesDto->getGroupName(),
            'coordinates' => $groupCoordinatesDto->getCoordinate(),
        ];
    }
}
