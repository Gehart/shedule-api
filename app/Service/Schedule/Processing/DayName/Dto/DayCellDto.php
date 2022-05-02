<?php

namespace App\Service\Schedule\Processing\DayName\Dto;

use App\Entities\Day;

class DayCellDto
{
    public function __construct(
        private Day $day,
        private string $cellRange,
    ) {
    }

    /**
     * @return string
     */
    public function getCellRange(): string
    {
        return $this->cellRange;
    }

    /**
     * @return Day
     */
    public function getDay(): Day
    {
        return $this->day;
    }
}
